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

        $rapports = Rapport::with('agence')
            ->when($user->role !== 'administrateur', fn ($q) => $q->where('utilisateur_id', $user->id))
            ->orderByDesc('date_generation')
            ->paginate(15);

        return view('rapports.index', ['rapports' => $rapports]);
    }

    /**
     * Génère un rapport. Dans cette version, la génération du fichier PDF/Excel/CSV
     * réel n'est pas implémentée (nécessite une librairie type barryvdh/laravel-dompdf
     * ou maatwebsite/excel, à installer via composer) — seul l'enregistrement en base
     * et le paramétrage sont fonctionnels ici.
     */
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
        $data['agence_id'] = in_array($user->role, ['administrateur', 'comptable'], true)
            ? ($data['agence_id'] ?? null) // null = rapport consolidé multi-agences
            : $user->agence_id;
        $data['date_generation'] = now();

        $rapport = Rapport::create($data);

        JournalActivite::enregistrer('generation_rapport', "Rapport « {$rapport->type_rapport} » généré ({$rapport->format_export})", $rapport);

        return back()->with('success', 'Rapport généré (export à implémenter — voir README).');
    }
}
