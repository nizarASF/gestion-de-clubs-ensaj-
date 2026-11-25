<?php

namespace Tests\Feature;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_bde_routes_are_forbidden_for_student_and_member_ok_for_bde(): void
    {
        $club = $this->club();
        $student = $this->user('student@test.fr');
        $member = $this->user('member@test.fr', Role::ClubMember, $club);
        $bde = $this->user('bde@test.fr', Role::Bde);

        $routes = ['admin.dashboard', 'admin.moderation', 'admin.users.index', 'admin.clubs.index', 'admin.posts.index'];

        // Le visiteur est vérifié avant tout actingAs (sinon la session reste authentifiée).
        foreach ($routes as $name) {
            $this->get(route($name))->assertRedirect(route('login'));
        }
        foreach ($routes as $name) {
            $this->actingAs($student)->get(route($name))->assertForbidden();
            $this->actingAs($member)->get(route($name))->assertForbidden();
            $this->actingAs($bde)->get(route($name))->assertOk();
        }
    }

    public function test_manage_routes_are_forbidden_for_student_and_ok_for_member(): void
    {
        $club = $this->club();
        $student = $this->user('student2@test.fr');
        $member = $this->user('member2@test.fr', Role::ClubMember, $club);

        $routes = ['manage.dashboard', 'manage.posts.index', 'manage.team.index', 'manage.media.index'];
        foreach ($routes as $name) {
            $this->get(route($name))->assertRedirect(route('login'));
        }
        foreach ($routes as $name) {
            $this->actingAs($student)->get(route($name))->assertForbidden();
            $this->actingAs($member)->get(route($name))->assertOk();
        }
    }
}
