<?php

namespace Database\Seeders;

use App\Models\Agence;
use Illuminate\Database\Seeder;

class AgenceSeeder extends Seeder
{
    public function run(): void
    {
        Agence::create(['nom' => 'Direction Générale', 'ville' => 'Lomé', 'adresse' => 'Kodjoviakopé, Lomé', 'date_ouverture' => '2001-04-23', 'est_siege' => true]);
        Agence::create(['nom' => 'Agence Kodjoviakopé', 'ville' => 'Lomé', 'adresse' => 'Kodjoviakopé, Lomé', 'date_ouverture' => '2001-04-23']);
        Agence::create(['nom' => 'Agence Agoè', 'ville' => 'Lomé', 'adresse' => 'Agoè-Nyivé', 'date_ouverture' => '2006-02-01']);
        Agence::create(['nom' => 'Agence Kara', 'ville' => 'Kara', 'adresse' => 'Centre-ville, Kara', 'date_ouverture' => '2010-09-15']);
        Agence::create(['nom' => 'Agence Sokodé', 'ville' => 'Sokodé', 'adresse' => 'Centre-ville, Sokodé', 'date_ouverture' => '2013-06-01']);
    }
}
