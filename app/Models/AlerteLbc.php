<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlerteLbc extends Model
{
    use HasFactory;

    protected $table = 'alertes_lbc';

    public const STATUT_NOUVELLE = 'nouvelle';
    public const STATUT_EN_COURS = 'en_cours_examen';
    public const STATUT_DECLAREE = 'declaree_cellule';
    public const STATUT_CLASSEE = 'classee_sans_suite';

    protected $fillable = [
        'transaction_id', 'societaire_id', 'motif', 'niveau_risque', 'statut',
        'traite_par', 'date_alerte', 'date_traitement', 'commentaire',
    ];

    protected function casts(): array
    {
        return [
            'date_alerte' => 'datetime',
            'date_traitement' => 'datetime',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par');
    }
}
