<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\JournalActivite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenceController extends Controller
{
    public function index(): View
    {
        $agences = Agence::withCount(['societaires', 'utilisateurs'])->orderBy('nom')->get();

        return view('admin.agences.index', ['agences' => $agences]);
    }

    public function create(): View
    {
        return view('admin.agences.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'date_ouverture' => ['nullable', 'date'],
            'est_siege' => ['sometimes', 'boolean'],
        ]);

        $agence = Agence::create($data);

        JournalActivite::enregistrer('creation_agence', "Création de l'agence {$agence->nom}", $agence);

        return redirect()->route('admin.agences.index')->with('success', 'Agence créée.');
    }
}
