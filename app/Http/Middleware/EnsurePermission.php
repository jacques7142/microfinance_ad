<?php

namespace App\Http\Middleware;

use App\Models\JournalActivite;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $slug): Response
    {
        $user = $request->user();

        if (!$user || !Permission::aPermission($user->role, $slug)) {
            JournalActivite::enregistrer(
                'acces_refuse',
                "Tentative d'accès à {$request->path()} sans la permission ({$slug})",
                statut: 'echec'
            );
            abort(403, "Action non autorisée pour votre profil.");
        }

        return $next($request);
    }
}