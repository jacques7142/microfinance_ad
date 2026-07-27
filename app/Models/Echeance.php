<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Echeance extends Model
{
    use HasFactory;

    public const STATUT_A_VENIR = 'a_venir';
    public const STATUT_PAYEE = 'payee';
    public const STATUT_EN_RETARD = 'en_retard';

    protected $fillable = ['credit_id', 'date_echeance', 'montant_du', 'montant_paye', 'statut'];

    protected function casts(): array
    {
        return [
            'date_echeance' => 'date',
            'montant_du' => 'decimal:2',
            'montant_paye' => 'decimal:2',
        ];
    }

    public function estEnRetard(): bool
    {
        return $this->statut !== self::STATUT_PAYEE && $this->date_echeance->isPast();
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }
}
