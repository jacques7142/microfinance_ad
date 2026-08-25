<?php

namespace App\Http\Controllers;

use App\Models\AlerteLbc;
use App\Models\CompteEpargne;
use App\Models\Credit;
use App\Models\Echeance;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\PaiementMobile;
use App\Services\LigdiCashService;
use App\Services\PaiementMobileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaiementMobileController extends Controller
{
    public function __construct(
        private readonly LigdiCashService $ligdiCash,
        private readonly PaiementMobileService $paiementService,
    ) {}

    /**
     * Webhook LigdiCash — reçoit les notifications de paiement (payin et payout).
     * LigdiCash envoie deux requêtes POST par événement (JSON + form), on garantit
     * l'idempotence via le statut de la PaiementMobile.
     */
    public function callback(Request $request): JsonResponse
    {
        // Le payload arrive en JSON ou en application/x-www-form-urlencoded.
        $payload = $request->json()->all();
        if (empty($payload)) {
            $payload = $request->all();
        }

        Log::info('Callback LigdiCash reçu.', ['payload' => $payload]);

        $reference = $this->extraireReference($payload);

        if (! $reference) {
            Log::warning('Callback LigdiCash sans référence interne.', ['payload' => $payload]);

            return response()->json(['error' => 'reference_introuvable'], 400);
        }

        $paiement = PaiementMobile::where('reference_interne', $reference)->first();

        if (! $paiement) {
            Log::warning('PaiementMobile introuvable pour la référence.', ['reference' => $reference]);

            return response()->json(['error' => 'paiement_introuvable'], 404);
        }

        // Idempotence : déjà finalisé, on ne retraite pas.
        if ($paiement->estFinalise()) {
            return response()->json(['ok' => true, 'duplicata' => true]);
        }

        try {
            if ($paiement->sens === PaiementMobile::SENS_PAYIN) {
                $confirmation = $this->ligdiCash->confirmPayin((string) $paiement->token);
            } else {
                $confirmation = $this->ligdiCash->confirmPayout((string) $paiement->token);
            }
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la confirmation LigdiCash.', [
                'reference' => $reference,
                'erreur' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'confirm_echec'], 502);
        }

        $statut = $confirmation['status'] ?? ($payload['status'] ?? null);

        if ($statut === 'completed') {
            $this->paiementService->finaliser($paiement, $payload);
        } elseif ($statut === 'notcompleted') {
            $paiement->finaliser(PaiementMobile::STATUT_NOTCOMPLETED, null, $payload);
        } else {
            // pending : on ne finalise pas, LigdiCash renverra un autre callback.
            $paiement->callback_payload = $payload;
            $paiement->save();
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Extrait la référence interne marchand depuis custom_data ou external_id.
     */
    protected function extraireReference(array $payload): ?string
    {
        if (! empty($payload['external_id']) && is_string($payload['external_id'])) {
            return $payload['external_id'];
        }

        $custom = $payload['custom_data'] ?? null;
        if (is_array($custom)) {
            foreach ($custom as $element) {
                if (is_array($element)
                    && ($element['keyof_customdata'] ?? null) === 'reference_interne'
                    && ! empty($element['valueof_customdata'])) {
                    return $element['valueof_customdata'];
                }
            }
        }

        return null;
    }

    /**
     * Interrogation de statut (AJAX) pour la page d'attente d'un paiement.
     */
    public function statut(Request $request, PaiementMobile $paiement): JsonResponse
    {
        if ($paiement->societaire_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json([
            'statut' => $paiement->statut,
            'finalise' => $paiement->estFinalise(),
        ]);
    }
}
