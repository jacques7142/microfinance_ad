<?php

namespace Database\Seeders;

use App\Models\Agence;
use Illuminate\Database\Seeder;

class AgenceSeeder extends Seeder
{
    public function run(): void
    {
        // Réseau officiel COOPEC-AD (32 agences + Direction Générale)
        // Source : https://coopecadtogo.com/agences/
        $agences = [
            // ---- Grand Lomé ----
            ['nom' => 'Direction Générale', 'ville' => 'Lomé', 'adresse' => '173, Av. Duisburg, Kodjoviakopé', 'date_ouverture' => '2001-04-23', 'est_siege' => true],
            ['nom' => 'Agence Abattoir', 'ville' => 'Abattoir, Lomé', 'adresse' => 'Quartier Abattoir, Lomé', 'date_ouverture' => '2003-01-15'],
            ['nom' => 'Agence Adétikopé', 'ville' => 'Adétikopé, Lomé', 'adresse' => 'Adétikopé, Lomé', 'date_ouverture' => '2005-06-20'],
            ['nom' => 'Agence Adidogomé', 'ville' => 'Adidogomé, Lomé', 'adresse' => 'Adidogomé, Lomé', 'date_ouverture' => '2006-02-01'],
            ['nom' => 'Agence Agbalépédo', 'ville' => 'Agbalépédo, Lomé', 'adresse' => 'Agbalépédo, Lomé', 'date_ouverture' => '2008-03-10'],
            ['nom' => 'Agence Agoè-Assiyéyé', 'ville' => 'Agoè-Assiyéyé, Lomé', 'adresse' => 'Agoè-Assiyéyé, Lomé', 'date_ouverture' => '2010-05-25'],
            ['nom' => 'Agence Agoè-Nyivé', 'ville' => 'Agoè-Nyivé, Lomé', 'adresse' => 'Agoè-Nyivé, Lomé', 'date_ouverture' => '2006-02-01'],
            ['nom' => 'Agence Assivito', 'ville' => 'Assivito, Lomé', 'adresse' => 'Assivito, Lomé', 'date_ouverture' => '2012-09-14'],
            ['nom' => 'Agence Bé-Anfamé', 'ville' => 'Bé-Anfamé, Lomé', 'adresse' => 'Bé-Anfamé, Lomé', 'date_ouverture' => '2009-11-03'],
            ['nom' => 'Agence Djagblé', 'ville' => 'Djagblé, Lomé', 'adresse' => 'Djagblé, Lomé', 'date_ouverture' => '2014-04-22'],
            ['nom' => 'Agence Gbényédji', 'ville' => 'Gbényédji, Lomé', 'adresse' => 'Gbényédji, Lomé', 'date_ouverture' => '2013-08-05'],
            ['nom' => 'Agence Kodjoviakopé', 'ville' => 'Lomé', 'adresse' => 'Kodjoviakopé, Lomé', 'date_ouverture' => '2001-04-23'],
            ['nom' => 'Agence Légbassito', 'ville' => 'Légbassito, Lomé', 'adresse' => 'Légbassito, Lomé', 'date_ouverture' => '2011-07-18'],
            ['nom' => 'Agence Lomé 2', 'ville' => 'Lomé', 'adresse' => 'Centre-ville, Lomé', 'date_ouverture' => '2004-12-01'],
            ['nom' => 'Agence Ramco', 'ville' => 'Ramco, Lomé', 'adresse' => 'Ramco, Lomé', 'date_ouverture' => '2015-02-10'],

            // ---- Maritime ----
            ['nom' => 'Agence Kévé', 'ville' => 'Kévé', 'adresse' => 'Centre-ville, Kévé', 'date_ouverture' => '2007-10-16'],
            ['nom' => 'Agence Tabligbo', 'ville' => 'Tabligbo', 'adresse' => 'Centre-ville, Tabligbo', 'date_ouverture' => '2010-03-08'],
            ['nom' => 'Agence Tsévié', 'ville' => 'Tsévié', 'adresse' => 'Centre-ville, Tsévié', 'date_ouverture' => '2006-08-21'],
            ['nom' => 'Agence Vogan', 'ville' => 'Vogan', 'adresse' => 'Centre-ville, Vogan', 'date_ouverture' => '2012-01-12'],

            // ---- Plateaux ----
            ['nom' => 'Agence Amou-Oblo', 'ville' => 'Amou-Oblo', 'adresse' => 'Amou-Oblo, Plateaux', 'date_ouverture' => '2019-06-01'],
            ['nom' => 'Agence Anié', 'ville' => 'Anié', 'adresse' => 'Centre-ville, Anié', 'date_ouverture' => '2014-10-27'],
            ['nom' => 'Agence Asrama', 'ville' => 'Asrama', 'adresse' => 'Asrama, Plateaux', 'date_ouverture' => '2016-05-09'],
            ['nom' => 'Agence Atakpamé', 'ville' => 'Atakpamé', 'adresse' => 'Centre-ville, Atakpamé', 'date_ouverture' => '2005-04-11'],
            ['nom' => 'Agence Badou', 'ville' => 'Badou', 'adresse' => 'Centre-ville, Badou', 'date_ouverture' => '2017-09-04'],
            ['nom' => 'Agence Kpalimé', 'ville' => 'Kpalimé', 'adresse' => 'Centre-ville, Kpalimé', 'date_ouverture' => '2008-02-25'],
            ['nom' => 'Agence Kpéklémé', 'ville' => 'Kpéklémé', 'adresse' => 'Kpéklémé, Plateaux', 'date_ouverture' => '2015-11-30'],
            ['nom' => 'Agence Notsé', 'ville' => 'Notsé', 'adresse' => 'Centre-ville, Notsé', 'date_ouverture' => '2011-04-13'],

            // ---- Centrale ----
            ['nom' => 'Agence Sokodé', 'ville' => 'Sokodé', 'adresse' => 'Centre-ville, Sokodé', 'date_ouverture' => '2013-06-01'],

            // ---- Kara ----
            ['nom' => 'Agence Bassar', 'ville' => 'Bassar', 'adresse' => 'Centre-ville, Bassar', 'date_ouverture' => '2016-08-22'],
            ['nom' => 'Agence Kara', 'ville' => 'Kara', 'adresse' => 'Centre-ville, Kara', 'date_ouverture' => '2010-09-15'],

            // ---- Savanes ----
            ['nom' => 'Agence Cinkassé', 'ville' => 'Cinkassé', 'adresse' => 'Centre-ville, Cinkassé', 'date_ouverture' => '2018-03-12'],
            ['nom' => 'Agence Dapaong', 'ville' => 'Dapaong', 'adresse' => 'Centre-ville, Dapaong', 'date_ouverture' => '2012-12-03'],
        ];

        // Harmonise l'agence de démo existante avec le nom officiel du réseau
        Agence::where('nom', 'Agence Agoè')->update([
            'nom' => 'Agence Agoè-Nyivé',
            'ville' => 'Agoè-Nyivé, Lomé',
            'adresse' => 'Agoè-Nyivé, Lomé',
        ]);

        foreach ($agences as $data) {
            Agence::firstOrCreate(['nom' => $data['nom']], $data);
        }
    }
}
