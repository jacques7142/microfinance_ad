<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\Rapport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RapportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $rapports = Rapport::with('agence', 'utilisateur')
            ->when(in_array($user->role, ['gerant', 'comptable']), fn ($q) => $q->where('agence_id', $user->agence_id))
            ->when(!in_array($user->role, ['administrateur', 'comptable', 'gerant']), fn ($q) => $q->where('utilisateur_id', $user->id))
            ->orderByDesc('date_generation')
            ->paginate(15);

        return view('rapports.index', ['rapports' => $rapports]);
    }

    private function autorise(Rapport $rapport): void
    {
        $user = request()->user();
        if ($user->role === 'administrateur') return;
        if (in_array($user->role, ['gerant', 'comptable']) && $rapport->agence_id === $user->agence_id) return;
        if ($rapport->utilisateur_id === $user->id) return;
        abort(403);
    }

    public function show(Request $request, Rapport $rapport): View
    {
        $this->autorise($rapport);

        return view('rapports.show', ['rapport' => $rapport->load('agence', 'utilisateur')]);
    }

    public function edit(Request $request, Rapport $rapport): View
    {
        $this->autorise($rapport);

        return view('rapports.edit', ['rapport' => $rapport]);
    }

    public function update(Request $request, Rapport $rapport): RedirectResponse
    {
        $this->autorise($rapport);

        $data = $request->validate([
            'type_rapport' => ['required', 'string', 'max:255'],
            'periode' => ['required', 'string', 'max:100'],
            'format_export' => ['required', 'in:pdf,excel,csv'],
        ]);

        $rapport->update($data);

        JournalActivite::enregistrer('modification_rapport', "Rapport « {$rapport->type_rapport} » modifié", $rapport);

        return redirect()->route('rapports.index')->with('success', 'Rapport mis à jour.');
    }

    public function destroy(Request $request, Rapport $rapport): RedirectResponse
    {
        $this->autorise($rapport);

        JournalActivite::enregistrer('suppression_rapport', "Rapport « {$rapport->type_rapport} » supprimé", $rapport);
        $rapport->delete();

        return redirect()->route('rapports.index')->with('success', 'Rapport supprimé.');
    }

    public function generer(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type_rapport' => ['required', 'string', 'max:255'],
            'periode' => ['required', 'string', 'max:100'],
            'format_export' => ['required', 'in:pdf,excel,csv'],
            'agence_id' => ['nullable', 'exists:agences,id'],
        ]);

        $user = $request->user();
        $data['utilisateur_id'] = $user->id;
        $data['agence_id'] = $user->role === 'administrateur'
            ? ($data['agence_id'] ?? null)
            : $user->agence_id;
        $data['date_generation'] = now();

        $rapport = Rapport::create($data);

        JournalActivite::enregistrer('generation_rapport', "Rapport « {$rapport->type_rapport} » généré ({$rapport->format_export})", $rapport);

        return back()->with('success', 'Rapport généré avec succès.');
    }
}
