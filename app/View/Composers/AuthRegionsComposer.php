<?php

namespace App\View\Composers;

use App\Models\Agence;
use App\Support\TogoRegions;
use Illuminate\View\View;

class AuthRegionsComposer
{
    /**
     * Injecte le réseau d'agences et les statistiques régionales dans les
     * vues d'authentification (login / inscription) afin d'alimenter la
     * carte du Togo cliquable sans modifier les contrôleurs existants.
     */
    public function compose(View $view): void
    {
        if ($view->offsetExists('agencesParRegion')) {
            return;
        }

        $agences = Agence::withCount('societaires')
            ->orderBy('nom')
            ->get();

        $view->with([
            'agencesParRegion' => TogoRegions::groupByRegion($agences),
            'regionsStats' => TogoRegions::stats($agences),
            'regionsListe' => TogoRegions::all(),
        ]);
    }
}
