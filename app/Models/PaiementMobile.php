<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementMobile extends Model
{
    use HasFactory;

    protected $table = 'paiement_mobile';

    public const TYPE_DEPOT = 'depot';

    public const TYPE_REMBOURSEMENT = 'remboursement';

    public const TYPE_RETRAIT = 'retrait';

    public const SENS_PAYIN = 'payin';

    public const SENS_PAYOUT = 'payout';

    public const OPERATEUR_YAS = 'yas';

    public const OPERATEUR_FLOOZ = 'flooz';

    public const STATUT_PENDING = 'pending';

    public const STATUT_COMPLETED = 'completed';

    public const STATUT_NOTCOMPLETED = 'notcompleted';

    public const STATUT_FAILED = 'failed';

    protected $fillable = [
        'societaire_id', 'reference_interne', 'type', 'sens', 'operateur', 'telephone', 'montant',
        'statut', 'compte_epargne_id', 'credit_id', 'echeance_id', 'transaction_id',
        'token', 'request_id', 'callback_payload', 'date_initiation', 'date_finalisation',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'callback_payload' => 'array',
            'date_initiation' => 'datetime',
            'date_finalisation' => 'datetime',
        ];
    }

    /** Libellé lisible de l'opérateur. */
    public function operateurLibelle(): string
    {
        return match ($this->operateur) {
            self::OPERATEUR_YAS => 'Mixx by Yas',
            self::OPERATEUR_FLOOZ => 'Flooz',
            default => $this->operateur,
        };
    }

    /** Libellé lisible du type d'opération. */
    public function typeLibelle(): string
    {
        return match ($this->type) {
            self::TYPE_DEPOT => 'Dépôt',
            self::TYPE_REMBOURSEMENT => 'Remboursement',
            self::TYPE_RETRAIT => 'Retrait',
            default => $this->type,
        };
    }

    public function estEnAttente(): bool
    {
        return $this->statut === self::STATUT_PENDING;
    }

    public function estFinalise(): bool
    {
        return in_array($this->statut, [self::STATUT_COMPLETED, self::STATUT_NOTCOMPLETED, self::STATUT_FAILED], true);
    }

    public function finaliser(string $statut, ?string $requestId = null, ?array $payload = null): void
    {
        $this->statut = $statut;
        $this->date_finalisation = now();
        if ($requestId) {
            $this->request_id = $requestId;
        }
        if ($payload !== null) {
            $this->callback_payload = $payload;
        }
        $this->save();
    }

    // --- Relations ---
    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function compteEpargne(): BelongsTo
    {
        return $this->belongsTo(CompteEpargne::class);
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }

    public function echeance(): BelongsTo
    {
        return $this->belongsTo(Echeance::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
