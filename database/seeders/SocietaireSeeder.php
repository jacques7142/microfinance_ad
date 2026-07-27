<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\Societaire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SocietaireSeeder extends Seeder
{
    public function run(): void
    {
        $agence = Agence::where('nom', 'Agence Kodjoviakopé')->first();

        $noms = [
            ['ADJOVI', 'Koffi'], ['BOKO', 'Afiwa'], ['TCHASSA', 'Kokou'],
            ['MENSAH', 'Yawa'], ['LAWSON', 'Kossi'], ['AZIABLE', 'Efui'],
            ['ATTIOGBE', 'Mawuli'], ['SEDZRO', 'Ablavi'], ['AGBOKA', 'Sitsofe'],
            ['DOGBE', 'Akouvi'],
        ];

        foreach ($noms as $i => [$nom, $prenom]) {
            Societaire::create([
                'agence_id' => $agence->id,
                'numero_societaire' => 'COOP-26-'.str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'nom' => $nom,
                'prenom' => $prenom,
                'telephone' => '9'.str_pad((string) (1000000 + $i), 8, '0', STR_PAD_LEFT),
                'adresse' => 'Kodjoviakopé, Lomé',
                'date_adhesion' => now()->subMonths(rand(2, 36)),
                'part_sociale' => 5000,
                'droit_adhesion' => 2000,
                'statut' => 'actif',
                'password' => Hash::make('coopecad2026'),
            ]);
        }
    }
}
