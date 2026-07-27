<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Societaire extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'agence_id', 'numero_societaire', 'nom', 'prenom', 'telephone', 'adresse',
        'date_adhesion', 'part_sociale', 'droit_adhesion', 'statut', 'email', 'password',
        'photo_profil', 'bio', 'derniere_modification_profil',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'date_adhesion' => 'date',
            'part_sociale' => 'decimal:2',
            'droit_adhesion' => 'decimal:2',
            'derniere_modification_profil' => 'datetime',
        ];
    }

    public function nomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    // --- Relations ---
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    public function comptesEpargne(): HasMany
    {
        return $this->hasMany(CompteEpargne::class);
    }

    public function compteTontine(): HasOne
    {
        return $this->hasOne(CompteTontine::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Solde total toutes épargnes confondues (DAV + DAT + tontine)
    public function soldeTotalEpargne(): float
    {
        return (float) $this->comptesEpargne()->sum('solde') + (float) ($this->compteTontine?->solde_accumule ?? 0);
    }
}
