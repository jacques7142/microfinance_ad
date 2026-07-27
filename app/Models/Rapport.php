<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id', 'agence_id', 'type_rapport', 'periode', 'format_export', 'date_generation',
    ];

    protected function casts(): array
    {
        return ['date_generation' => 'datetime'];
    }

    public function estMultiAgences(): bool
    {
        return is_null($this->agence_id);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }
}
