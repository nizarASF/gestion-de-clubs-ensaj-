<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\Status;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_editing_rejected_team_member_sets_it_back_to_pending(): void
    {
        $club = $this->club();
        $member = $this->user('member@test.fr', Role::ClubMember, $club);
        $bde = $this->user('bde@test.fr', Role::Bde);

        $fiche = new TeamMember([
            'name' => 'Sarah Benali',
            'position' => 'Présidente',
            'academic_year' => '2025-2026',
            'sort_order' => 0,
        ]);
        $fiche->club_id = $club->id;
        $fiche->proposed_by = $member->id;
        $fiche->status = Status::Rejected;
        $fiche->rejection_reason = 'Photo manquante.';
        $fiche->save();

        $this->actingAs($member)->put(route('manage.team.update', $fiche), [
            'name' => 'Sarah Benali',
            'position' => 'Présidente',
            'academic_year' => '2025-2026',
        ])->assertRedirect(route('manage.team.index'));

        $fiche->refresh();
        $this->assertSame(Status::Pending, $fiche->status);
        $this->assertNull($fiche->rejection_reason);
    }

    public function test_only_approved_team_members_are_public(): void
    {
        $club = $this->club();
        $member = $this->user('member@test.fr', Role::ClubMember, $club);
        $bde = $this->user('bde@test.fr', Role::Bde);

        $fiche = new TeamMember([
            'name' => 'Omar Idrissi',
            'position' => 'Trésorier',
            'academic_year' => '2025-2026',
        ]);
        $fiche->club_id = $club->id;
        $fiche->proposed_by = $member->id;
        $fiche->status = Status::Pending;
        $fiche->save();

        $this->get(route('clubs.team', $club))->assertDontSee('Omar Idrissi');

        $this->actingAs($bde)->post(route('admin.moderation.approve', ['team', $fiche->id]))->assertRedirect();
        $this->get(route('clubs.team', $club))->assertSee('Omar Idrissi');
    }
}
