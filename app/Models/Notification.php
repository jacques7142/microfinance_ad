<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['societaire_id', 'type', 'contenu', 'date_envoi', 'statut_envoi', 'lu', 'lien'];

    protected function casts(): array
    {
        return [
            'date_envoi' => 'datetime',
            'lu' => 'boolean',
        ];
    }

    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }
}
