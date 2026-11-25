<?php

namespace Tests\Feature;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_role_keeps_club_id_consistent(): void
    {
        $club = $this->club();
        $user = $this->user('test@test.fr');
        $user->club_id = $club->id;
        $user->save();

        $user->assignRole(Role::Student, $club->id);
        $this->assertSame(Role::Student, $user->fresh()->role);
        $this->assertNull($user->fresh()->club_id);

        $user->assignRole(Role::ClubMember, $club->id);
        $this->assertSame(Role::ClubMember, $user->fresh()->role);
        $this->assertSame($club->id, $user->fresh()->club_id);

        $user->assignRole(Role::Bde, $club->id);
        $this->assertSame(Role::Bde, $user->fresh()->role);
        $this->assertNull($user->fresh()->club_id);
    }
}
