<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class JournalActivite extends Model
{
    use HasFactory;

    protected $table = 'journaux_activite';

    public $timestamps = true;

    protected $fillable = [
        'user_id', 'societaire_id', 'action', 'description',
        'cible_type', 'cible_id', 'adresse_ip', 'user_agent', 'statut', 'date_action',
    ];

    protected function casts(): array
    {
        return ['date_action' => 'datetime'];
    }

    /** Journalise une action en une ligne — utilisé par le middleware LogActivite et les contrôleurs sensibles. */
    public static function enregistrer(string $action, ?string $description = null, ?Model $cible = null, string $statut = 'succes'): self
    {
        $user = auth()->user() ?? Auth::guard('societaire')->user();

        return self::create([
            'user_id' => $user?->getTable() === 'users' ? $user->id : null,
            'societaire_id' => $user?->getTable() === 'societaires' ? $user->id : null,
            'action' => $action,
            'description' => $description,
            'cible_type' => $cible ? class_basename($cible) : null,
            'cible_id' => $cible?->getKey(),
            'adresse_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'statut' => $statut,
            'date_action' => now(),
        ]);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function societaire(): BelongsTo
    {
        return $this->belongsTo(Societaire::class);
    }
}
