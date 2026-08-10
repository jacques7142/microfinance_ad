<?php

namespace App\Http\Middleware;

use App\Models\JournalActivite;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Protège une route pour un ou plusieurs rôles internes.
     * Usage dans routes/web.php : ->middleware('role:gerant,administrateur')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->aUnRole($roles)) {
            JournalActivite::enregistrer(
                'acces_refuse',
                "Tentative d'accès à {$request->path()} sans le rôle requis (".implode(',', $roles).')',
                statut: 'echec'
            );

            abort(403, "Vous n'avez pas accès à cette section.");
        }

        return $next($request);
    }
}
