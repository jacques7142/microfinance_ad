<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollecteTontine extends Model
{
    use HasFactory;

    protected $table = 'collectes_tontine';

    protected $fillable = [
        'compte_tontine_id', 'agent_promotion_id', 'caissier_id',
        'date_collecte', 'montant', 'lieu', 'geolocalisation',
        'mode_confirmation', 'statut_validation',
    ];

    protected function casts(): array
    {
        return [
            'date_collecte' => 'datetime',
            'montant' => 'decimal:2',
        ];
    }

    // --- Relations ---
    public function compteTontine(): BelongsTo
    {
        return $this->belongsTo(CompteTontine::class);
    }

    public function agentPromotion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_promotion_id');
    }

    public function caissier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caissier_id');
    }
}
