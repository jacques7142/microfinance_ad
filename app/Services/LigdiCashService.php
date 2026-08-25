<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class LigdiCashService
{
    /**
     * Endpoints LigdiCash.
     */
    public const EP_PAYIN_CREATE = '/pay/v01/straight/checkout-invoice/create';

    public const EP_PAYIN_CONFIRM = '/pay/v01/redirect/checkout-invoice/confirm';

    public const EP_PAYOUT_CREATE = '/pay/v01/straight/payout';

    public const EP_PAYOUT_CONFIRM = '/pay/v01/withdrawal/confirm';

    /**
     * Indique si les identifiants API LigdiCash sont configurés.
     */
    public function configure(): bool
    {
        return (bool) (config('services.ligdicash.api_key') && config('services.ligdicash.auth_token'));
    }

    /**
     * Mode démo : le flux de paiement (USSD push) est simulé localement afin de
     * pouvoir présenter l'application au jury sans identifiants LigdiCash réels.
     *
     * - LIGDICASH_DEMO=true  → démo forcée
     * - LIGDICASH_DEMO=false → mode réel (appelle l'API)
     * - LIGDICASH_DEMO=null  → démo automatique si les identifiants manquent
     */
    public function estEnModeDemo(): bool
    {
        $demo = config('services.ligdicash.demo');

        if ($demo !== null && $demo !== '') {
            return filter_var($demo, FILTER_VALIDATE_BOOL);
        }

        return ! $this->configure();
    }

    /**
     * Client HTTP préconfiguré avec les en-têtes d'authentification LigdiCash.
     */
    protected function http(): PendingRequest
    {
        return Http::withHeaders([
            'Apikey' => config('services.ligdicash.api_key'),
            'Authorization' => 'Bearer '.config('services.ligdicash.auth_token'),
            'Accept' => 'application/json',
        ])
            ->acceptJson()
            ->asJson()
            ->baseUrl(config('services.ligdicash.base_url'))
            ->timeout(30);
    }

    /**
     * Payin (encaissement) — déclenche un USSD Push sur le téléphone du sociétaire.
     *
     * @param  array  $data  ['compte', 'montant', 'description', 'telephone', 'nom', 'prenom']
     */
    public function createPayin(array $data, string $reference, string $callbackUrl): array
    {
        if ($this->estEnModeDemo()) {
            return $this->reponseSimulee($reference);
        }

        if (! $this->configure()) {
            throw new \RuntimeException('Identifiants LigdiCash non configurés (LIGDICASH_API_KEY / LIGDICASH_AUTH_TOKEN).');
        }

        $payload = [
            'commande' => [
                'invoice' => [
                    'items' => [],
                    'total_amount' => (int) $data['montant'],
                    'devise' => 'XOF',
                    'description' => $data['description'] ?? '',
                    'customer' => $this->normaliserTelephone($data['telephone']),
                    'customer_firstname' => $data['prenom'] ?? '',
                    'customer_lastname' => $data['nom'] ?? '',
                    'customer_email' => $data['email'] ?? '',
                    'external_id' => $reference,
                    'otp' => '',
                ],
                'store' => [
                    'name' => config('services.ligdicash.store_name'),
                    'website_url' => config('app.url'),
                ],
                'actions' => [
                    'cancel_url' => '',
                    'return_url' => '',
                    'callback_url' => $callbackUrl,
                ],
                'custom_data' => [
                    'reference_interne' => $reference,
                ],
            ],
        ];

        return $this->requete(self::EP_PAYIN_CREATE, $payload);
    }

    /**
     * Confirme le statut d'un payin à partir du token stocké à la création.
     */
    public function confirmPayin(string $token): array
    {
        if ($this->estEnModeDemo()) {
            return $this->confirmationSimulee($token);
        }

        if (! $this->configure()) {
            throw new \RuntimeException('Identifiants LigdiCash non configurés (LIGDICASH_API_KEY / LIGDICASH_AUTH_TOKEN).');
        }

        $reponse = $this->http()->get(self::EP_PAYIN_CONFIRM, ['invoiceToken' => $token]);

        return $reponse->json() ?? [];
    }

    /**
     * Payout (décaissement) — envoi de fonds vers le numéro mobile money du sociétaire.
     *
     * @param  array  $data  ['montant', 'description', 'telephone']
     */
    public function createPayout(array $data, string $reference, string $callbackUrl): array
    {
        if ($this->estEnModeDemo()) {
            return $this->reponseSimulee($reference);
        }

        if (! $this->configure()) {
            throw new \RuntimeException('Identifiants LigdiCash non configurés (LIGDICASH_API_KEY / LIGDICASH_AUTH_TOKEN).');
        }

        $payload = [
            'commande' => [
                'amount' => (int) $data['montant'],
                'description' => $data['description'] ?? '',
                'customer' => $this->normaliserTelephone($data['telephone']),
                'callback_url' => $callbackUrl,
                'custom_data' => [
                    'reference_interne' => $reference,
                ],
            ],
        ];

        return $this->requete(self::EP_PAYOUT_CREATE, $payload);
    }

    /**
     * Confirme le statut d'un payout à partir du token stocké à la création.
     */
    public function confirmPayout(string $token): array
    {
        if ($this->estEnModeDemo()) {
            return $this->confirmationSimulee($token);
        }

        if (! $this->configure()) {
            throw new \RuntimeException('Identifiants LigdiCash non configurés (LIGDICASH_API_KEY / LIGDICASH_AUTH_TOKEN).');
        }

        $reponse = $this->http()->get(self::EP_PAYOUT_CONFIRM, ['withdrawalToken' => $token]);

        return $reponse->json() ?? [];
    }

    /**
     * Réponse simulée (mode démo) renvoyée lors de la création d'un payin/payout.
     */
    protected function reponseSimulee(string $reference): array
    {
        return [
            'response_code' => '00',
            'response_text' => 'Paiement simulé (mode démo). Aucun appel à LigdiCash.',
            'token' => 'demo-'.substr(md5($reference), 0, 12),
            'external_id' => $reference,
            'demo' => true,
        ];
    }

    /**
     * Confirmation simulée (mode démo) : le paiement est considéré comme abouti.
     */
    protected function confirmationSimulee(string $token): array
    {
        return [
            'status' => 'completed',
            'token' => $token,
            'response_code' => '00',
            'demo' => true,
        ];
    }

    /**
     * Exécute une requête POST vers un endpoint LigdiCash et gère les erreurs.
     */
    protected function requete(string $endpoint, array $payload): array
    {
        try {
            $reponse = $this->http()->post($endpoint, $payload);

            if ($reponse->clientError() || $reponse->serverError()) {
                throw new \RuntimeException('LigdiCash a répondu avec le statut HTTP '.$reponse->status().' : '.$reponse->body());
            }

            return $reponse->json() ?? [];
        } catch (ConnectionException $e) {
            throw new \RuntimeException('Impossible de joindre LigdiCash : '.$e->getMessage());
        }
    }

    /**
     * Normalise un numéro au format international (228XXXXXXXX), sans '+' ni espaces.
     */
    public function normaliserTelephone(string $telephone): string
    {
        $nettoye = preg_replace('/[^0-9]/', '', $telephone);

        // Déjà au format international complet (8 chiffres précédés de l'indicatif).
        if (strlen($nettoye) === 11) {
            return $nettoye;
        }

        // Numéro local (8 chiffres) : on ajoute l'indicatif Togo.
        if (strlen($nettoye) === 8) {
            return '228'.$nettoye;
        }

        return $nettoye;
    }
}
