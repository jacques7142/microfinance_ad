<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rôles additionnels d'un utilisateur interne.
 * Le rôle "principal" reste stocké sur la colonne users.role ; cette table
 * permet d'attribuer plusieurs rôles à un même utilisateur.
 */
class UserRole extends Model
{
    protected $table = 'user_role';

    public $timestamps = true;

    protected $fillable = ['user_id', 'role'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
