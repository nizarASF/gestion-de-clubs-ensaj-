<?php

namespace Database\Seeders;

use App\Enums\MessageType;
use App\Enums\Status;
use App\Models\Club;
use App\Models\Conversation;
use App\Models\Post;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Quelques contenus pour que le site ne soit pas vide au premier lancement. */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $ais = Club::where('slug', 'ais')->firstOrFail();
        $bde = User::where('email', 'bde@ensaj.test')->firstOrFail();
        $member = User::where('email', 'membre.ais@ensaj.test')->firstOrFail();
        $student = User::where('email', 'etudiant@ensaj.test')->firstOrFail();

        Post::firstOrCreate([
            'club_id' => $ais->id,
            'title' => 'Bienvenue au club AIS',
        ], [
            'author_id' => $bde->id,
            'body' => "Découvrez nos activités de l'année et rejoignez-nous !\nRendez-vous chaque jeudi à 16h.",
            'status' => Status::Approved,
            'published_at' => now()->subDays(3),
            'validated_by' => $bde->id,
            'validated_at' => now()->subDays(3),
        ]);

        Post::firstOrCreate([
            'club_id' => $ais->id,
            'title' => 'Projet hackathon (à valider)',
        ], [
            'author_id' => $member->id,
            'body' => 'Nous préparons un hackathon inter-clubs, en attente de validation du BDE.',
            'status' => Status::Pending,
        ]);

        foreach (['2024-2025', '2025-2026'] as $year) {
            foreach ([['Sarah Benali', 'Présidente'], ['Omar Idrissi', 'Vice-président']] as $index => [$name, $position]) {
                TeamMember::firstOrCreate([
                    'club_id' => $ais->id,
                    'name' => $name,
                    'academic_year' => $year,
                ], [
                    'proposed_by' => $member->id,
                    'position' => $position,
                    'sort_order' => $index,
                    'status' => Status::Approved,
                    'validated_by' => $bde->id,
                    'validated_at' => now()->subDays(2),
                ]);
            }
        }

        $conversation = Conversation::firstOrCreate([
            'club_id' => $ais->id,
            'user_id' => $student->id,
        ], ['last_message_at' => now()->subHour()]);

        if ($conversation->messages()->count() === 0) {
            $conversation->messages()->create([
                'author_id' => $student->id,
                'body' => 'Bonjour, comment rejoindre le club AIS ?',
                'type' => MessageType::Question,
                'from_club' => false,
                'created_at' => now()->subHour(),
            ]);
            $conversation->messages()->create([
                'author_id' => $member->id,
                'body' => 'Bonjour ! Venez nous voir jeudi à 16h, salle B12.',
                'from_club' => true,
                'created_at' => now()->subMinutes(45),
            ]);
        }
    }
}
