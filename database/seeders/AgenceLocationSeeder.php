<?php

namespace Database\Seeders;

use App\Models\Agence;
use Illuminate\Database\Seeder;

class AgenceLocationSeeder extends Seeder
{
    public function run(): void
    {
        // Horaires réseau (source : coopecadtogo.com) — Lun-Ven 07:30-16:30, Sam 08:00-12:00
        $horaires = function (): array {
            $semaine = [
                'lundi' => ['ouverture' => '07:30', 'fermeture' => '16:30'],
                'mardi' => ['ouverture' => '07:30', 'fermeture' => '16:30'],
                'mercredi' => ['ouverture' => '07:30', 'fermeture' => '16:30'],
                'jeudi' => ['ouverture' => '07:30', 'fermeture' => '16:30'],
                'vendredi' => ['ouverture' => '07:30', 'fermeture' => '16:30'],
            ];
            return $semaine + [
                'samedi' => ['ouverture' => '08:00', 'fermeture' => '12:00'],
                'dimanche' => ['ouverture' => 'Fermé', 'fermeture' => 'Fermé'],
            ];
        };

        // Coordonnées GPS approximatives + téléphones officiels (source : coopecadtogo.com/agences/)
        $locations = [
            // ---- Grand Lomé ----
            'Direction Générale' => [
                'latitude' => 6.1256, 'longitude' => 1.2317, 'secteur' => 'Kodjoviakopé',
                'telephone_agence' => '+228 96 80 32 87',
                'description' => 'Direction Nationale de COOPEC-AD. Centre de pilotage et de coordination du réseau au 173, Av. Duisburg, Kodjoviakopé.',
            ],
            'Agence Abattoir' => [
                'latitude' => 6.1520, 'longitude' => 1.2280, 'secteur' => 'Abattoir',
                'telephone_agence' => '+228 79 32 60 30',
                'description' => 'Agence COOPEC-AD du quartier Abattoir, Grand Lomé.',
            ],
            'Agence Adétikopé' => [
                'latitude' => 6.1680, 'longitude' => 1.2490, 'secteur' => 'Adétikopé',
                'description' => 'Agence COOPEC-AD d\'Adétikopé, Grand Lomé.',
            ],
            'Agence Adidogomé' => [
                'latitude' => 6.1670, 'longitude' => 1.1980, 'secteur' => 'Adidogomé',
                'telephone_agence' => '+228 99 34 72 32',
                'description' => 'Agence COOPEC-AD d\'Adidogomé, Grand Lomé.',
            ],
            'Agence Agbalépédo' => [
                'latitude' => 6.2050, 'longitude' => 1.2610, 'secteur' => 'Agbalépédo',
                'telephone_agence' => '+228 99 34 16 11',
                'description' => 'Agence COOPEC-AD d\'Agbalépédo, Grand Lomé.',
            ],
            'Agence Agoè-Assiyéyé' => [
                'latitude' => 6.2630, 'longitude' => 1.2980, 'secteur' => 'Agoè-Assiyéyé',
                'telephone_agence' => '+228 96 29 53 11',
                'description' => 'Agence COOPEC-AD d\'Agoè-Assiyéyé, Grand Lomé.',
            ],
            'Agence Agoè-Nyivé' => [
                'latitude' => 6.2500, 'longitude' => 1.2830, 'secteur' => 'Agoè-Nyivé',
                'telephone_agence' => '+228 99 34 16 12',
                'description' => 'Agence COOPEC-AD d\'Agoè-Nyivé, Grand Lomé.',
            ],
            'Agence Assivito' => [
                'latitude' => 6.2150, 'longitude' => 1.2780, 'secteur' => 'Assivito',
                'telephone_agence' => '+228 99 34 16 10',
                'description' => 'Agence COOPEC-AD d\'Assivito, Grand Lomé.',
            ],
            'Agence Bé-Anfamé' => [
                'latitude' => 6.1830, 'longitude' => 1.2150, 'secteur' => 'Bé-Anfamé',
                'telephone_agence' => '+228 99 34 16 09',
                'description' => 'Agence COOPEC-AD de Bé-Anfamé, Grand Lomé.',
            ],
            'Agence Djagblé' => [
                'latitude' => 6.2330, 'longitude' => 1.2150, 'secteur' => 'Djagblé',
                'telephone_agence' => '+228 98 48 93 68',
                'description' => 'Agence COOPEC-AD de Djagblé, Grand Lomé.',
            ],
            'Agence Gbényédji' => [
                'latitude' => 6.2350, 'longitude' => 1.2960, 'secteur' => 'Gbényédji',
                'telephone_agence' => '+228 99 34 16 07',
                'description' => 'Agence COOPEC-AD de Gbényédji, Grand Lomé.',
            ],
            'Agence Kodjoviakopé' => [
                'latitude' => 6.1250, 'longitude' => 1.2310, 'secteur' => 'Kodjoviakopé',
                'telephone_agence' => '+228 99 34 16 06',
                'description' => 'Agence principale COOPEC-AD à Kodjoviakopé, Grand Lomé.',
            ],
            'Agence Légbassito' => [
                'latitude' => 6.1980, 'longitude' => 1.2470, 'secteur' => 'Légbassito',
                'telephone_agence' => '+228 98 48 93 68',
                'description' => 'Agence COOPEC-AD de Légbassito, Grand Lomé.',
            ],
            'Agence Lomé 2' => [
                'latitude' => 6.1330, 'longitude' => 1.2150, 'secteur' => 'Centre-ville',
                'telephone_agence' => '+228 99 14 35 68',
                'description' => 'Agence COOPEC-AD Lomé 2, centre-ville de Lomé.',
            ],
            'Agence Ramco' => [
                'latitude' => 6.1500, 'longitude' => 1.1980, 'secteur' => 'Ramco',
                'telephone_agence' => '+228 98 48 93 71',
                'description' => 'Agence COOPEC-AD de Ramco, Grand Lomé.',
            ],

            // ---- Maritime ----
            'Agence Kévé' => [
                'latitude' => 6.4167, 'longitude' => 0.9167, 'secteur' => 'Kévé',
                'telephone_agence' => '+228 96 50 33 69',
                'description' => 'Agence COOPEC-AD de Kévé, région Maritime.',
            ],
            'Agence Tabligbo' => [
                'latitude' => 6.5833, 'longitude' => 1.5000, 'secteur' => 'Tabligbo',
                'telephone_agence' => '+228 99 34 16 14',
                'description' => 'Agence COOPEC-AD de Tabligbo, région Maritime.',
            ],
            'Agence Tsévié' => [
                'latitude' => 6.4333, 'longitude' => 1.2167, 'secteur' => 'Tsévié',
                'telephone_agence' => '+228 99 34 16 13',
                'description' => 'Agence COOPEC-AD de Tsévié, région Maritime.',
            ],
            'Agence Vogan' => [
                'latitude' => 6.3333, 'longitude' => 1.5333, 'secteur' => 'Vogan',
                'telephone_agence' => '+228 97 92 70 00',
                'description' => 'Agence COOPEC-AD de Vogan, région Maritime.',
            ],

            // ---- Plateaux ----
            'Agence Amou-Oblo' => [
                'latitude' => 7.5833, 'longitude' => 0.8833, 'secteur' => 'Amou-Oblo',
                'telephone_agence' => '+228 99 34 16 17',
                'description' => 'Agence COOPEC-AD d\'Amou-Oblo, région Plateaux.',
            ],
            'Agence Anié' => [
                'latitude' => 7.7500, 'longitude' => 1.2000, 'secteur' => 'Anié',
                'telephone_agence' => '+228 98 48 93 77',
                'description' => 'Agence COOPEC-AD d\'Anié, région Plateaux.',
            ],
            'Agence Asrama' => [
                'latitude' => 7.2333, 'longitude' => 0.9333, 'secteur' => 'Asrama',
                'telephone_agence' => '+228 99 34 16 19',
                'description' => 'Agence COOPEC-AD d\'Asrama, région Plateaux.',
            ],
            'Agence Atakpamé' => [
                'latitude' => 7.5262, 'longitude' => 1.1280, 'secteur' => 'Atakpamé',
                'telephone_agence' => '+228 99 34 16 21',
                'description' => 'Agence COOPEC-AD d\'Atakpamé, chef-lieu de la région Plateaux.',
            ],
            'Agence Badou' => [
                'latitude' => 7.5833, 'longitude' => 0.6000, 'secteur' => 'Badou',
                'telephone_agence' => '+228 96 50 33 70',
                'description' => 'Agence COOPEC-AD de Badou, région Plateaux.',
            ],
            'Agence Kpalimé' => [
                'latitude' => 6.9000, 'longitude' => 0.6281, 'secteur' => 'Kpalimé',
                'telephone_agence' => '+228 99 34 16 15',
                'description' => 'Agence COOPEC-AD de Kpalimé, région Plateaux.',
            ],
            'Agence Kpéklémé' => [
                'latitude' => 6.8833, 'longitude' => 0.7500, 'secteur' => 'Kpéklémé',
                'telephone_agence' => '+228 99 34 16 18',
                'description' => 'Agence COOPEC-AD de Kpéklémé, région Plateaux.',
            ],
            'Agence Notsé' => [
                'latitude' => 6.9500, 'longitude' => 1.1667, 'secteur' => 'Notsé',
                'telephone_agence' => '+228 99 34 16 20',
                'description' => 'Agence COOPEC-AD de Notsé, région Plateaux.',
            ],

            // ---- Centrale ----
            'Agence Sokodé' => [
                'latitude' => 8.9833, 'longitude' => 1.1333, 'secteur' => 'Sokodé',
                'telephone_agence' => '+228 99 34 16 22',
                'description' => 'Agence COOPEC-AD de Sokodé, chef-lieu de la région Centrale.',
            ],

            // ---- Kara ----
            'Agence Bassar' => [
                'latitude' => 9.2500, 'longitude' => 0.7833, 'secteur' => 'Bassar',
                'telephone_agence' => '+228 96 50 33 71',
                'description' => 'Agence COOPEC-AD de Bassar, région Kara.',
            ],
            'Agence Kara' => [
                'latitude' => 9.5512, 'longitude' => 1.1861, 'secteur' => 'Kara',
                'telephone_agence' => '+228 99 34 16 23',
                'description' => 'Agence COOPEC-AD de Kara, chef-lieu de la région Kara.',
            ],

            // ---- Savanes ----
            'Agence Cinkassé' => [
                'latitude' => 11.2500, 'longitude' => 0.1500, 'secteur' => 'Cinkassé',
                'telephone_agence' => '+228 99 34 16 25',
                'description' => 'Agence COOPEC-AD de Cinkassé, région Savanes.',
            ],
            'Agence Dapaong' => [
                'latitude' => 10.8625, 'longitude' => 0.2075, 'secteur' => 'Dapaong',
                'telephone_agence' => '+228 99 34 16 24',
                'description' => 'Agence COOPEC-AD de Dapaong, chef-lieu de la région Savanes.',
            ],
        ];

        foreach ($locations as $agenceName => $data) {
            Agence::where('nom', $agenceName)->update($data + [
                'horaires_fonctionnement' => $horaires(),
            ]);
        }
    }
}
