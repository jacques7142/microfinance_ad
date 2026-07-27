<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenceDetailController extends Controller
{
    public function show(Agence $agence): View
    {
        $gerant = $agence->gerant();
        $statAgence = $agence->actif ? 'Actif' : 'Inactif';
        
        return view('agences.detail', [
            'agence' => $agence,
            'gerant' => $gerant,
            'statAgence' => $statAgence,
            'coordonnees' => [
                'lat' => $agence->latitude ?? 6.1256,
                'lng' => $agence->longitude ?? 1.2317,
            ],
        ]);
    }

    public function modal(Agence $agence)
    {
        $gerant = $agence->gerant();
        
        return response()->json([
            'agence' => $agence,
            'gerant' => $gerant ? [
                'nom' => $gerant->nom,
                'prenom' => $gerant->prenom,
                'email' => $gerant->email,
                'telephone' => $gerant->telephone,
            ] : null,
            'coordonnees' => [
                'lat' => $agence->latitude ?? 6.1256,
                'lng' => $agence->longitude ?? 1.2317,
            ],
        ]);
    }
}
