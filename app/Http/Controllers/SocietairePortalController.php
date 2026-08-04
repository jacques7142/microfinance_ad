<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SocietairePortalController extends Controller
{
    public function dashboard(): View
    {
        $societaire = Auth::guard('societaire')
            ->user()
            ->load([
                'agence',
                'comptesEpargne',
                'compteTontine',
                'credits.echeances',
            ]);

        return view('societaires.portal', [
            'societaire' => $societaire,
            'credits' => $societaire->credits()->orderByDesc('date_demande')->limit(5)->get(),
            'sousTypesOrdinaire' => Credit::SOUS_TYPES_ORDINAIRE,
        ]);
    }
}
