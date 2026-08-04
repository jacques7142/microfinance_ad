<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['nom', 'slug', 'groupe', 'description'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_permission', 'permission_id', 'role', 'id', 'role');
    }

    public static function roleList(): array
    {
        return User::ROLES;
    }

    public static function permissionsParRole(string $role): array
    {
        return self::join('role_permission', 'permissions.id', '=', 'role_permission.permission_id')
            ->where('role_permission.role', $role)
            ->pluck('permissions.slug')
            ->toArray();
    }

    public static function aPermission(string $role, string $slug): bool
    {
        return self::join('role_permission', 'permissions.id', '=', 'role_permission.permission_id')
            ->where('role_permission.role', $role)
            ->where('permissions.slug', $slug)
            ->exists();
    }
}