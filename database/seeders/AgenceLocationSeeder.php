<?php

namespace Database\Seeders;

use App\Models\Agence;
use Illuminate\Database\Seeder;

class AgenceLocationSeeder extends Seeder
{
    public function run(): void
    {
        // Données de localisation pour les agences
        $locations = [
            'Direction Générale' => [
                'latitude' => 6.1256,
                'longitude' => 1.2317,
                'secteur' => 'Kodjoviakopé',
                'description' => 'Direction Générale de COOPEC-AD. Centre de pilotage et coordination de toutes les opérations du réseau.',
                'telephone_agence' => '+228 XXX-XXXX',
                'horaires_fonctionnement' => [
                    'lundi' => ['ouverture' => '08:00', 'fermeture' => '17:00'],
                    'mardi' => ['ouverture' => '08:00', 'fermeture' => '17:00'],
                    'mercredi' => ['ouverture' => '08:00', 'fermeture' => '17:00'],
                    'jeudi' => ['ouverture' => '08:00', 'fermeture' => '17:00'],
                    'vendredi' => ['ouverture' => '08:00', 'fermeture' => '17:00'],
                    'samedi' => ['ouverture' => '09:00', 'fermeture' => '13:00'],
                    'dimanche' => ['ouverture' => 'Fermé', 'fermeture' => 'Fermé'],
                ],
            ],
            'Agence Kodjoviakopé' => [
                'latitude' => 6.1250,
                'longitude' => 1.2310,
                'secteur' => 'Kodjoviakopé',
                'description' => 'Agence principale à Kodjoviakopé. Service complet de microfinance et épargne.',
                'telephone_agence' => '+228 XXX-XXXX',
                'horaires_fonctionnement' => [
                    'lundi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mardi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mercredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'jeudi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'vendredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'samedi' => ['ouverture' => '08:30', 'fermeture' => '12:00'],
                    'dimanche' => ['ouverture' => 'Fermé', 'fermeture' => 'Fermé'],
                ],
            ],
            'Agence Agoè' => [
                'latitude' => 6.1100,
                'longitude' => 1.2500,
                'secteur' => 'Agoè-Nyivé',
                'description' => 'Agence d\'Agoè-Nyivé. Services financiers et accompagnement entrepreneurial.',
                'telephone_agence' => '+228 XXX-XXXX',
                'horaires_fonctionnement' => [
                    'lundi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mardi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mercredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'jeudi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'vendredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'samedi' => ['ouverture' => '08:30', 'fermeture' => '12:00'],
                    'dimanche' => ['ouverture' => 'Fermé', 'fermeture' => 'Fermé'],
                ],
            ],
            'Agence Kara' => [
                'latitude' => 9.5632,
                'longitude' => 1.2583,
                'secteur' => 'Kara',
                'description' => 'Agence régionale de Kara. Services complets couvrant la région nord du Togo.',
                'telephone_agence' => '+228 XXX-XXXX',
                'horaires_fonctionnement' => [
                    'lundi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mardi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mercredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'jeudi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'vendredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'samedi' => ['ouverture' => '08:30', 'fermeture' => '12:00'],
                    'dimanche' => ['ouverture' => 'Fermé', 'fermeture' => 'Fermé'],
                ],
            ],
            'Agence Sokodé' => [
                'latitude' => 8.9754,
                'longitude' => 1.1367,
                'secteur' => 'Sokodé',
                'description' => 'Agence de Sokodé. Point de services majeur de la région centrale du Togo.',
                'telephone_agence' => '+228 XXX-XXXX',
                'horaires_fonctionnement' => [
                    'lundi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mardi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'mercredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'jeudi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'vendredi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
                    'samedi' => ['ouverture' => '08:30', 'fermeture' => '12:00'],
                    'dimanche' => ['ouverture' => 'Fermé', 'fermeture' => 'Fermé'],
                ],
            ],
        ];

        foreach ($locations as $agenceName => $data) {
            Agence::where('nom', $agenceName)->update($data);
        }
    }
}
