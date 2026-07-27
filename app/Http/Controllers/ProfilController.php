<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $authType = Auth::guard('societaire')->check() ? 'societaire' : 'user';
        
        return view('profil.show', [
            'user' => $user,
            'authType' => $authType,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        $authType = Auth::guard('societaire')->check() ? 'societaire' : 'user';
        
        return view('profil.edit', [
            'user' => $user,
            'authType' => $authType,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $authType = Auth::guard('societaire')->check() ? 'societaire' : 'user';
        
        // Validation
        $table = $authType === 'societaire' ? 'societaires' : 'users';
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:' . $table . ',email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Gestion de la photo de profil
        if ($request->hasFile('photo_profil')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
                Storage::disk('public')->delete($user->photo_profil);
            }
            
            // Sauvegarder la nouvelle photo
            $path = $request->file('photo_profil')->store('profils', 'public');
            $validated['photo_profil'] = $path;
        }

        $validated['derniere_modification_profil'] = now();

        $user->update($validated);

        return redirect()->route('profil.show')
            ->with('success', 'Profil mis à jour avec succès!');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = Auth::user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        // Sauvegarder la nouvelle photo
        $path = $request->file('photo')->store('profils', 'public');
        $user->update([
            'photo_profil' => $path,
            'derniere_modification_profil' => now(),
        ]);

        return response()->json([
            'success' => true,
            'photo_url' => asset('storage/' . $path),
        ]);
    }
}
