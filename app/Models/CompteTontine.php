<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompteTontine extends Model
{
    use HasFactory;

    // ====================== AJOUT IMPORTANT ======================
    protected $table = 'comptes_tontine';
    // ============================================================

    protected $fillable = [
        'societaire_id', 
        'solde_accumule', 
        'mise_habituelle', 
        'zone_tournee', 
        'date_adhesion',
    ];

    protected function casts(): array
    {
        return [
            'date_adhesion' => 'date',
            'solde_accumule' => 'decimal:2',
            'mise_habituelle' => 'decimal:2',
        ];
    }

    // Méthode métier
    public function plafondCreditAdosse(float $proportion = 0.70): float
    {
        return round((float) $this->solde_accumule * $proportion, 2);
    }

    // Relations
    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function collectes(): HasMany
    {
        return $this->hasMany(CollecteTontine::class, 'compte_tontine_id');
    }

    public function creditsAdosses(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}