<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\CompteEpargne;
use App\Models\Document;
use App\Models\JournalActivite;
use App\Models\Societaire;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
            'agences' => Agence::orderBy('nom')->get(['id', 'nom', 'ville']),
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
            'piece_identite' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'signature' => ['required', 'string'],
        ]);

        $data['numero_societaire'] = 'COOP-'.now()->format('y').'-'.str_pad((string) (Societaire::max('id') + 1), 5, '0', STR_PAD_LEFT);
        $data['date_adhesion'] = now();
        $data['statut'] = 'actif';
        $data['part_sociale'] = 0;
        $data['droit_adhesion'] = 0;
        $data['password'] = Hash::make($data['password']);

        $societaire = Societaire::create(Arr::except($data, ['signature']));

        CompteEpargne::create([
            'societaire_id' => $societaire->id,
            'type' => CompteEpargne::TYPE_DAV,
            'solde' => 0,
            'date_ouverture' => now(),
        ]);

        if ($request->hasFile('piece_identite')) {
            $file = $request->file('piece_identite');
            $chemin = $file->store('documents/pieces', 'public');

            Document::create([
                'societaire_id' => $societaire->id,
                'type_piece' => 'cni',
                'chemin_fichier' => $chemin,
                'nom_original' => $file->getClientOriginalName(),
                'statut_verification' => Document::STATUT_EN_ATTENTE,
                'date_televersement' => now(),
            ]);
        }

        // Sauvegarder la signature électronique
        if ($request->filled('signature')) {
            $signatureData = $request->input('signature');
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $decoded = base64_decode($signatureData);

            $nomFichier = 'signature_' . $societaire->id . '_' . time() . '.png';
            $cheminSignature = 'documents/signatures/' . $nomFichier;
            \Illuminate\Support\Facades\Storage::disk('public')->put($cheminSignature, $decoded);

            Document::create([
                'societaire_id' => $societaire->id,
                'type_piece' => 'signature',
                'chemin_fichier' => $cheminSignature,
                'nom_original' => $nomFichier,
                'statut_verification' => Document::STATUT_VALIDE,
                'date_televersement' => now(),
            ]);
        }

        Auth::guard('societaire')->login($societaire);
        $request->session()->regenerate();

        JournalActivite::enregistrer('inscription_societaire', "Inscription du sociétaire {$societaire->numero_societaire}", $societaire);

        return redirect()->intended(route('societaire.dashboard'));
    }
}
