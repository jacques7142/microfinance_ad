<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public const TYPE_DEPOT = 'depot';
    public const TYPE_RETRAIT = 'retrait';
    public const TYPE_REMBOURSEMENT = 'remboursement';
    public const TYPE_COLLECTE_TONTINE = 'collecte_tontine';
    public const TYPE_DECAISSEMENT_CREDIT = 'decaissement_credit';
    public const TYPE_CORRECTION = 'correction';

    protected $fillable = [
        'agence_id', 'utilisateur_id', 'societaire_id', 'compte_epargne_id', 'compte_tontine_id', 'credit_id',
        'type', 'montant', 'date_operation', 'statut', 'corrigee',
    ];

    protected function casts(): array
    {
        return [
            'date_operation' => 'datetime',
            'montant' => 'decimal:2',
            'corrigee' => 'boolean',
        ];
    }

    // --- Relations ---
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function compteEpargne(): BelongsTo
    {
        return $this->belongsTo(CompteEpargne::class);
    }

    public function compteTontine(): BelongsTo
    {
        return $this->belongsTo(CompteTontine::class);
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }

    public function alerteLbc()
    {
        return $this->hasOne(AlerteLbc::class);
    }
}
