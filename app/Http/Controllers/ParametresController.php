<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParametresController extends Controller
{
    public function index(): View
    {
        $authType = Auth::guard('societaire')->check() ? 'societaire' : 'user';
        $utilisateur = $authType === 'societaire'
            ? Auth::guard('societaire')->user()
            : Auth::user();

        return view('parametres.index', [
            'utilisateur' => $utilisateur,
            'authType' => $authType,
            'version' => config('app.version', '1.0.0'),
            'nomApp' => config('app.name', 'COOPEC-AD'),
        ]);
    }
}
