<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompteEpargne extends Model
{
    use HasFactory;

    public const TYPE_DAV = 'DAV';
    public const TYPE_DAT = 'DAT';

    protected $table = 'comptes_epargne';

    protected $fillable = [
        'societaire_id', 'type', 'solde', 'date_ouverture',
        'plafond_retrait_journalier', 'date_echeance', 'taux_remuneration', 'duree_blocage_mois',
    ];

    protected function casts(): array
    {
        return [
            'date_ouverture' => 'date',
            'date_echeance' => 'date',
            'solde' => 'decimal:2',
            'plafond_retrait_journalier' => 'decimal:2',
            'taux_remuneration' => 'decimal:2',
        ];
    }

    public function estDAV(): bool
    {
        return $this->type === self::TYPE_DAV;
    }

    public function estDAT(): bool
    {
        return $this->type === self::TYPE_DAT;
    }

    // --- Relations ---
    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
