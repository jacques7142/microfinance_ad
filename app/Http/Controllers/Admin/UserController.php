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
        $utilisateurs = User::with('agence', 'rolesAdditionnels')->orderBy('role')->orderBy('nom')->paginate(25);

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
            'roles_additionnels' => ['nullable', 'array'],
            'roles_additionnels.*' => ['in:'.implode(',', User::ROLES)],
            'agence_id' => ['required', 'exists:agences,id'],
            'seuil_validation' => ['nullable', 'numeric', 'min:0'],
            'zone_tournee' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $rolesAdditionnels = array_values(array_diff(
            $data['roles_additionnels'] ?? [],
            [$data['role']]
        ));

        $data['password'] = Hash::make($data['password']);
        $data['actif'] = true;

        $user = User::create($data);
        $user->syncRolesAdditionnels($rolesAdditionnels);

        JournalActivite::enregistrer('creation_utilisateur', "Création du compte {$user->email} ({$user->role})", $user);

        return redirect()->route('admin.users.index')->with('success', "Utilisateur créé. Mot de passe à communiquer à {$user->nomComplet()} : {$request->password}");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'agences' => Agence::orderBy('nom')->get(),
            'roles' => User::ROLES,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'telephone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:'.implode(',', User::ROLES)],
            'roles_additionnels' => ['nullable', 'array'],
            'roles_additionnels.*' => ['in:'.implode(',', User::ROLES)],
            'agence_id' => ['required', 'exists:agences,id'],
            'seuil_validation' => ['nullable', 'numeric', 'min:0'],
            'zone_tournee' => ['nullable', 'string', 'max:255'],
            'couleur' => ['nullable', 'string', 'max:7'],
        ]);

        $user->update($data);

        $user->syncRolesAdditionnels(array_values(array_diff(
            $data['roles_additionnels'] ?? [],
            [$data['role']]
        )));

        JournalActivite::enregistrer('modification_utilisateur', "Modification du compte {$user->email}", $user);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $email = $user->email;
        $user->delete();

        JournalActivite::enregistrer('suppression_utilisateur', "Suppression du compte {$email}");

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    public function updateCouleur(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'couleur' => ['required', 'string', 'max:7'],
        ]);

        $user->update(['couleur' => $data['couleur']]);

        JournalActivite::enregistrer('modification_utilisateur', "Couleur attribuée à {$user->email}", $user);

        return back()->with('success', 'Couleur mise à jour.');
    }

    public function toggleActif(User $user): RedirectResponse
    {
        $user->update(['actif' => ! $user->actif]);

        JournalActivite::enregistrer('modification_utilisateur', ($user->actif ? 'Réactivation' : 'Désactivation')." du compte {$user->email}", $user);

        return back()->with('success', 'Statut mis à jour.');
    }
}
