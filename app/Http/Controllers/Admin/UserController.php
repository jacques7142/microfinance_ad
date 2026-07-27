<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $utilisateurs = User::with('agence')->orderBy('role')->orderBy('nom')->paginate(25);

        return view('admin.users.index', ['utilisateurs' => $utilisateurs]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'agences' => Agence::orderBy('nom')->get(),
            'roles' => User::ROLES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:'.implode(',', User::ROLES)],
            'agence_id' => ['required', 'exists:agences,id'],
            'seuil_validation' => ['nullable', 'numeric', 'min:0'],
            'zone_tournee' => ['nullable', 'string', 'max:255'],
        ]);

        $data['password'] = Hash::make('coopecad2026'); // mot de passe temporaire — à faire changer à la première connexion
        $data['actif'] = true;

        $user = User::create($data);

        JournalActivite::enregistrer('creation_utilisateur', "Création du compte {$user->email} ({$user->role})", $user);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé. Mot de passe temporaire : coopecad2026');
    }

    public function toggleActif(User $user): RedirectResponse
    {
        $user->update(['actif' => ! $user->actif]);

        JournalActivite::enregistrer('modification_utilisateur', ($user->actif ? 'Réactivation' : 'Désactivation')." du compte {$user->email}", $user);

        return back()->with('success', 'Statut mis à jour.');
    }
}
