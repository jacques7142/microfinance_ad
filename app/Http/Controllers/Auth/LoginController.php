<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\JournalActivite;
use App\Models\Societaire;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login', [
            'agenceCount' => Agence::count(),
            'societaireCount' => Societaire::count(),
            'utilisateurCount' => User::count(),
            'profilCount' => User::select('role')->distinct()->count(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = trim($data['identifier']);

        // 1. Tentative connexion interne (User) par email
        $credentials = ['email' => $identifier, 'password' => $data['password']];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            JournalActivite::enregistrer('connexion', 'Connexion réussie');

            $user = Auth::user();

            $route = match ($user->role) {
                User::ROLE_ADMIN => 'dashboard',
                User::ROLE_GERANT => 'dashboard',
                User::ROLE_AGENT_CREDIT => 'dashboard',
                User::ROLE_AGENT_PROMOTION => 'dashboard',
                User::ROLE_CAISSIER => 'dashboard',
                User::ROLE_COMPTABLE => 'dashboard',
                default => 'dashboard',
            };

            return redirect()->intended(route($route));
        }

        // 2. Tentative connexion sociétaire par téléphone ou numéro sociétaire
        $normalizedPhone = preg_replace('/\D+/', '', $identifier);
        $normalizedNumero = strtoupper(str_replace(' ', '', $identifier));

        $societaire = Societaire::where(function ($query) use ($identifier, $normalizedPhone, $normalizedNumero) {
            $query->where('telephone', $normalizedPhone)
                  ->orWhere('numero_societaire', $identifier)
                  ->orWhere('numero_societaire', $normalizedNumero);
        })->first();

        if ($societaire && Hash::check($data['password'], $societaire->password)) {
            Auth::guard('societaire')->login($societaire);
            $request->session()->regenerate();
            JournalActivite::enregistrer('connexion', 'Connexion sociétaire réussie');

            // On force la redirection vers l'espace sociétaire : l'URL "intended"
            // peut pointer vers une page interne (web guard) inaccessible au
            // sociétaire, ce qui provoquerait une boucle de redirection.
            $request->session()->forget('url.intended');

            return redirect()->route('societaire.dashboard');
        }

        // 3. Échec
        JournalActivite::enregistrer(
            'connexion',
            "Échec de connexion pour {$identifier}",
            statut: 'echec'
        );

        return back()->withErrors(['identifier' => 'Identifiants incorrects.'])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        JournalActivite::enregistrer('deconnexion', 'Déconnexion');

        Auth::guard('web')->logout();
        Auth::guard('societaire')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
