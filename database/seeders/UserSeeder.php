<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $siege = Agence::where('est_siege', true)->first();
        $kodjoviakope = Agence::where('nom', 'Agence Kodjoviakopé')->first();

        $motDePasse = Hash::make('coopecad2026');

        User::create([
            'agence_id' => $siege->id,
            'nom' => 'ADMIN', 'prenom' => 'Système',
            'email' => 'admin@coopec-ad.tg', 'password' => $motDePasse,
            'telephone' => '90000001', 'role' => User::ROLE_ADMIN, 'actif' => true,
        ]);

        User::create([
            'agence_id' => $kodjoviakope->id,
            'nom' => 'TOGBE', 'prenom' => 'Essowè',
            'email' => 'gerant@coopec-ad.tg', 'password' => $motDePasse,
            'telephone' => '90000002', 'role' => User::ROLE_GERANT,
            'seuil_validation' => 500000, 'actif' => true,
        ]);

        User::create([
            'agence_id' => $kodjoviakope->id,
            'nom' => 'KUEVI', 'prenom' => 'Ayaba',
            'email' => 'agentcredit@coopec-ad.tg', 'password' => $motDePasse,
            'telephone' => '90000003', 'role' => User::ROLE_AGENT_CREDIT, 'actif' => true,
        ]);

        User::create([
            'agence_id' => $kodjoviakope->id,
            'nom' => 'AMEGAN', 'prenom' => 'Koffi',
            'email' => 'agentpromotion@coopec-ad.tg', 'password' => $motDePasse,
            'telephone' => '90000004', 'role' => User::ROLE_AGENT_PROMOTION,
            'zone_tournee' => 'Kodjoviakopé Centre', 'actif' => true,
        ]);

        User::create([
            'agence_id' => $kodjoviakope->id,
            'nom' => 'SEDZRO', 'prenom' => 'Mawuena',
            'email' => 'caissier@coopec-ad.tg', 'password' => $motDePasse,
            'telephone' => '90000005', 'role' => User::ROLE_CAISSIER, 'actif' => true,
        ]);

        User::create([
            'agence_id' => $siege->id,
            'nom' => 'DOSSEH', 'prenom' => 'Ama',
            'email' => 'comptable@coopec-ad.tg', 'password' => $motDePasse,
            'telephone' => '90000006', 'role' => User::ROLE_COMPTABLE, 'actif' => true,
        ]);
    }
}
