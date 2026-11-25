<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_pending_student_and_ignores_role_from_request(): void
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'Nouveau',
            'last_name' => 'Étudiant',
            'email' => 'nouveau@ensaj.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'field_of_study' => 'gi',
            'role' => 'bde',
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Inscription enregistrée. Votre compte doit être validé par le BDE avant de pouvoir vous connecter.');

        $user = User::where('email', 'nouveau@ensaj.test')->firstOrFail();
        $this->assertSame('student', $user->role->value);
        $this->assertSame('pending', $user->status->value);
        $this->assertNull($user->club_id);
    }

    public function test_login_is_refused_for_pending_and_rejected_accounts(): void
    {
        $pending = $this->user('attente@ensaj.test', status: \App\Enums\Status::Pending);
        $rejected = $this->user('refuse@ensaj.test', status: \App\Enums\Status::Rejected);
        $rejected->rejection_reason = 'Informations incomplètes.';
        $rejected->save();

        $this->post(route('login.store'), ['email' => $pending->email, 'password' => 'password'])
            ->assertSessionHasErrors(['email' => 'Votre compte est en attente de validation par le BDE.']);

        $this->post(route('login.store'), ['email' => $rejected->email, 'password' => 'password'])
            ->assertSessionHasErrors(['email' => 'Votre inscription a été refusée. Motif : Informations incomplètes.']);

        $this->assertGuest();
    }

    public function test_approved_user_can_login_and_wrong_password_gives_generic_error(): void
    {
        $user = $this->user('ok@ensaj.test');

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'mauvais'])
            ->assertSessionHasErrors(['email' => 'Identifiants incorrects.']);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }
}
