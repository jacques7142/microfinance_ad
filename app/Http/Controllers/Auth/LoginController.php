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
use Illuminate\Validation\Rule;
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
            'account_type' => ['required', Rule::in(['interne', 'societaire'])],
            'identifier' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if ($data['account_type'] === 'societaire') {
            $identifier = trim($data['identifier']);
            $normalizedPhone = preg_replace('/\D+/', '', $identifier);
            $normalizedNumero = strtoupper(str_replace(' ', '', $identifier));

            $societaire = Societaire::where(function ($query) use ($identifier, $normalizedPhone, $normalizedNumero) {
                $query->where('telephone', $normalizedPhone)
                      ->orWhere('numero_societaire', $identifier)
                      ->orWhere('numero_societaire', $normalizedNumero);
            })->first();

            if (! $societaire || ! Hash::check($data['password'], $societaire->password)) {
                JournalActivite::enregistrer(
                    'connexion',
                    "Échec de connexion sociétaire pour {$data['identifier']}",
                    statut: 'echec'
                );

                return back()->withErrors(['identifier' => 'Identifiants incorrects.'])->withInput();
            }

            Auth::guard('societaire')->login($societaire);
            $request->session()->regenerate();
            JournalActivite::enregistrer('connexion', 'Connexion sociétaire réussie');

            return redirect()->intended(route('societaire.dashboard'));
        }

        $credentials = ['email' => $data['identifier'], 'password' => $data['password']];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            JournalActivite::enregistrer(
                'connexion',
                "Échec de connexion pour {$data['identifier']}",
                statut: 'echec'
            );

            return back()->withErrors(['identifier' => 'Identifiants incorrects.'])->withInput();
        }

        $request->session()->regenerate();
        JournalActivite::enregistrer('connexion', 'Connexion réussie');

        return redirect()->intended(route('dashboard'));
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
