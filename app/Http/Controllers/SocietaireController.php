<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\Societaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocietaireController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $societaires = Societaire::with('agence')
            ->when($user->role !== 'administrateur' && $user->role !== 'comptable', fn ($q) => $q->where('agence_id', $user->agence_id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->string('q');
                $q->where(fn ($sub) => $sub->where('nom', 'like', "%$term%")
                    ->orWhere('prenom', 'like', "%$term%")
                    ->orWhere('numero_societaire', 'like', "%$term%"));
            })
            ->orderBy('nom')
            ->paginate(20)
            ->withQueryString();

        return view('societaires.index', ['societaires' => $societaires]);
    }

    public function create(): View
    {
        return view('societaires.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30', 'unique:societaires,telephone'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'part_sociale' => ['required', 'numeric', 'min:0'],
            'droit_adhesion' => ['required', 'numeric', 'min:0'],
        ]);

        $data['agence_id'] = $request->user()->agence_id;
        $data['numero_societaire'] = 'COOP-'.now()->format('y').'-'.str_pad((string) (Societaire::max('id') + 1), 5, '0', STR_PAD_LEFT);
        $data['date_adhesion'] = now();
        $data['statut'] = 'actif';

        $societaire = Societaire::create($data);

        JournalActivite::enregistrer('creation_societaire', "Création du sociétaire {$societaire->numero_societaire}", $societaire);

        return redirect()->route('societaires.show', $societaire)->with('success', 'Sociétaire enregistré.');
    }

    public function show(Societaire $societaire): View
    {
        $societaire->load(['comptesEpargne', 'compteTontine.collectes', 'credits.echeances', 'documents']);

        return view('societaires.show', ['societaire' => $societaire]);
    }
}
