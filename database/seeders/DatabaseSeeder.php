<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgenceSeeder::class,
            UserSeeder::class,
            SocietaireSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
