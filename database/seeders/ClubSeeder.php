<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = require database_path('seeders/data/clubs.php');

        foreach ($clubs as $data) {
            $logoPath = null;
            $logo = database_path('seeders/assets/logos/' . $data['slug'] . '.png');
            if (is_file($logo)) {
                $logoPath = 'logos/' . $data['slug'] . '.png';
                Storage::disk('public')->put($logoPath, file_get_contents($logo));
            }

            Club::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'logo_path' => $logoPath,
                    'is_active' => true,
                ]
            );
        }
    }
}
