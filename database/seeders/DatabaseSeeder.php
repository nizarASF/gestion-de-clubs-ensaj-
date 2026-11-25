<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ordre imposé : clubs → comptes → contenus de démonstration.
        $this->call([
            ClubSeeder::class,
            UserSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
