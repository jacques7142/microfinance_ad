<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Credit;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\Societaire;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register', [
            'agenceCount' => Agence::count(),
            'societaireCount' => Societaire::count(),
            'utilisateurCount' => User::count(),
            'profilCount' => User::select('role')->distinct()->count(),
            'agences' => Agence::orderBy('nom')->get(['id', 'nom']),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'telephone' => ['required', 'string', 'max:30', 'unique:societaires,telephone'],
            'email' => ['nullable', 'email', 'max:255', 'unique:societaires,email'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'agence_id' => ['required', 'exists:agences,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'in:'.implode(',', [Credit::TYPE_ORDINAIRE, Credit::TYPE_PARTENARIAT])],
            'sous_type' => ['nullable', 'required_if:type,'.Credit::TYPE_ORDINAIRE, 'in:'.implode(',', Credit::SOUS_TYPES_ORDINAIRE)],
            'partenaire' => ['nullable', 'required_if:type,'.Credit::TYPE_PARTENARIAT, 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:1000'],
            'duree_mois' => ['required', 'integer', 'min:1', 'max:60'],
            'taux_interet' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $data['numero_societaire'] = 'COOP-'.now()->format('y').'-'.str_pad((string) (Societaire::max('id') + 1), 5, '0', STR_PAD_LEFT);
        $data['date_adhesion'] = now();
        $data['statut'] = 'actif';
        $data['part_sociale'] = 0;
        $data['droit_adhesion'] = 0;
        $data['password'] = Hash::make($data['password']);

        $societaire = Societaire::create($data);

        $credit = Credit::create([
            'societaire_id' => $societaire->id,
            'type' => $data['type'],
            'sous_type' => $data['sous_type'] ?? null,
            'partenaire' => $data['partenaire'] ?? null,
            'montant' => $data['montant'],
            'duree_mois' => $data['duree_mois'],
            'taux_interet' => $data['taux_interet'],
            'date_demande' => now(),
            'statut' => Credit::STATUT_RECUE,
        ]);

        Auth::guard('societaire')->login($societaire);
        $request->session()->regenerate();

        JournalActivite::enregistrer('inscription_societaire', "Inscription du sociétaire {$societaire->numero_societaire}", $societaire);
        JournalActivite::enregistrer('creation_credit', "Demande de crédit reçue #{$credit->id}", $credit);

        Notification::create([
            'societaire_id' => $societaire->id,
            'type' => 'sms',
            'contenu' => "Votre demande de crédit de {$credit->montant} F a bien été reçue. Nous vous tiendrons informé de la suite.",
            'date_envoi' => now(),
            'statut_envoi' => 'envoyee',
        ]);

        return redirect()->intended(route('societaire.dashboard'));
    }
}
