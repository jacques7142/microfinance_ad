<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Les 6 rôles internes portés par ce modèle unique (héritage à table unique)
    public const ROLE_ADMIN = 'administrateur';
    public const ROLE_GERANT = 'gerant';
    public const ROLE_AGENT_CREDIT = 'agent_credit';
    public const ROLE_AGENT_PROMOTION = 'agent_promotion';
    public const ROLE_CAISSIER = 'caissier';
    public const ROLE_COMPTABLE = 'comptable';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_GERANT,
        self::ROLE_AGENT_CREDIT,
        self::ROLE_AGENT_PROMOTION,
        self::ROLE_CAISSIER,
        self::ROLE_COMPTABLE,
    ];

    protected $fillable = [
        'agence_id', 'nom', 'prenom', 'email', 'password', 'telephone',
        'role', 'seuil_validation', 'zone_tournee', 'actif', 'couleur', 'photo_profil', 'bio', 'derniere_modification_profil',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
            'seuil_validation' => 'decimal:2',
            'derniere_modification_profil' => 'datetime',
        ];
    }

    public function nomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function estRole(string $role): bool
    {
        return $this->role === $role;
    }

    /** Vérifie si l'utilisateur possède le rôle (principal ou additionnel). */
    public function hasRole(string $role): bool
    {
        return $this->role === $role
            || $this->rolesAdditionnels()->where('role', $role)->exists();
    }

    /** Vérifie si l'utilisateur possède au moins un des rôles donnés. */
    public function aUnRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /** Tous les rôles attribués (principal d'abord, puis les additionnels). */
    public function rolesAttribues(): array
    {
        return array_values(array_unique([
            $this->role,
            ...$this->rolesAdditionnels->pluck('role')->all(),
        ]));
    }

    // --- Relations ---
    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }

    /** Rôles additionnels attribués à cet utilisateur. */
    public function rolesAdditionnels(): HasMany
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    /** Remplace la liste des rôles additionnels d'un utilisateur. */
    public function syncRolesAdditionnels(array $roles): void
    {
        $this->rolesAdditionnels()->whereNotIn('role', $roles)->delete();

        foreach ($roles as $role) {
            $this->rolesAdditionnels()->firstOrCreate(['role' => $role]);
        }
    }

    public function creditsInstruits(): HasMany
    {
        return $this->hasMany(Credit::class, 'agent_credit_id');
    }

    public function creditsValides(): HasMany
    {
        return $this->hasMany(Credit::class, 'gerant_id');
    }

    public function collectesEnregistrees(): HasMany
    {
        return $this->hasMany(CollecteTontine::class, 'agent_promotion_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'utilisateur_id');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class, 'utilisateur_id');
    }
}
