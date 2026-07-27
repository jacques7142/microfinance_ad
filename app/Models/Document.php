<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_VALIDE = 'valide';
    public const STATUT_REJETE = 'rejete';
    public const STATUT_EXPIRE = 'expire';

    protected $fillable = [
        'societaire_id', 'type_piece', 'chemin_fichier', 'nom_original',
        'statut_verification', 'verifie_par', 'date_verification', 'motif_rejet',
        'date_expiration', 'date_televersement',
    ];

    protected function casts(): array
    {
        return [
            'date_verification' => 'datetime',
            'date_expiration' => 'date',
            'date_televersement' => 'datetime',
        ];
    }

    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function verificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }
}
