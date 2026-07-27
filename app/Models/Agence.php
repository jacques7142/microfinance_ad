<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'ville', 'adresse', 'date_ouverture', 'est_siege', 'latitude', 'longitude', 'secteur', 'telephone_agence', 'description', 'horaires_fonctionnement', 'actif'];

    protected $casts = [
        'date_ouverture' => 'date',
        'est_siege' => 'boolean',
        'actif' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'horaires_fonctionnement' => 'array',
    ];

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function gerant()
    {
        return $this->utilisateurs()->where('role', 'gerant')->first();
    }

    public function societaires(): HasMany
    {
        return $this->hasMany(Societaire::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class);
    }
}
