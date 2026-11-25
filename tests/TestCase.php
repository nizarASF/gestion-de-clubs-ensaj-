<?php

namespace Tests;

use App\Enums\Role;
use App\Enums\Status;
use App\Models\Club;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** Fabriques simples : pas de dépendance à Eloquent factories. */
    protected function club(string $slug = 'ais'): Club
    {
        return Club::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => ucfirst($slug),
                'short_description' => 'Club de test',
                'description' => 'Description de test',
                'is_active' => true,
            ]
        );
    }

    protected function user(string $email, Role $role = Role::Student, ?Club $club = null, Status $status = Status::Approved): User
    {
        $user = User::firstOrNew(['email' => $email]);
        $user->first_name = 'Test';
        $user->last_name = 'User';
        $user->password = bcrypt('password');
        $user->field_of_study = 'gi';
        $user->status = $status;
        $user->save();
        $user->assignRole($role, $club?->id);

        return $user;
    }

    protected function createPost(Club $club, User $author, Status $status, string $title, string $body = 'Texte de test'): Post
    {
        // Les champs techniques sont affectés côté serveur, comme dans les contrôleurs.
        $post = new Post(['title' => $title, 'body' => $body]);
        $post->club_id = $club->id;
        $post->author_id = $author->id;
        $post->status = $status;
        if ($status === Status::Approved) {
            $post->published_at = now()->subDay();
        }
        $post->save();

        return $post;
    }
}
