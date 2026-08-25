<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\JournalActivite;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SocietaireCreditController extends Controller
{
    public function create(): View
    {
        return view('societaires.credit_request', [
            'sousTypesOrdinaire' => Credit::SOUS_TYPES_ORDINAIRE,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:'.implode(',', [Credit::TYPE_ORDINAIRE, Credit::TYPE_PARTENARIAT, Credit::TYPE_TONTINE])],
            'sous_type' => ['nullable', 'required_if:type,'.Credit::TYPE_ORDINAIRE, 'in:'.implode(',', Credit::SOUS_TYPES_ORDINAIRE)],
            'partenaire' => ['nullable', 'required_if:type,'.Credit::TYPE_PARTENARIAT, 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:1000'],
            'duree_mois' => ['required', 'integer', 'min:1', 'max:60'],
            'taux_interet' => ['required', 'numeric', 'min:0', 'max:100'],
            'signature' => ['required', 'string'],
        ]);

        $societaire = Auth::guard('societaire')->user();

        if ($data['type'] === Credit::TYPE_TONTINE) {
            $compteTontine = $societaire->compteTontine;

            if (! $compteTontine) {
                return back()->withErrors(['type' => 'Vous devez disposer d’un compte tontine LOGOKU actif pour ce type de crédit.'])->withInput();
            }

            $plafond = $compteTontine->plafondCreditAdosse();
            if ($data['montant'] > $plafond) {
                return back()->withErrors(['montant' => "Montant supérieur au plafond de garantie tontine ({$plafond} F)."])->withInput();
            }

            $data['compte_tontine_id'] = $compteTontine->id;
            $data['proportion_garantie'] = round(($data['montant'] / (float) $compteTontine->solde_accumule) * 100, 2);
        }

        $data['societaire_id'] = $societaire->id;
        $data['date_demande'] = now();
        $data['statut'] = Credit::STATUT_RECUE;
        $data['signature_societaire'] = $data['signature'];
        $data['signe_le'] = now();

        $credit = Credit::create($data);

        JournalActivite::enregistrer('creation_credit', "Demande de crédit reçue #{$credit->id}", $credit);

        Notification::create([
            'societaire_id' => $societaire->id,
            'type' => 'sms',
            'contenu' => "Votre demande de crédit de {$credit->montant} F a bien été reçue. Nous vous tiendrons informé de la suite.",
            'date_envoi' => now(),
            'statut_envoi' => 'envoyee',
            'lien' => route('societaire.mon-compte'),
        ]);

        return redirect()->route('societaire.dashboard')->with('success', 'Votre demande de crédit a été enregistrée.');
    }
}
