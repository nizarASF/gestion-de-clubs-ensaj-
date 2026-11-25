<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\Status;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_post_is_hidden_until_bde_approves_it(): void
    {
        $club = $this->club();
        $bde = $this->user('bde@test.fr', Role::Bde);
        $member = $this->user('member@test.fr', Role::ClubMember, $club);

        $post = $this->createPost($club, $member, Status::Pending, 'Actualité secrète', 'Un texte de test');

        $this->get(route('clubs.show', $club))->assertDontSee('Actualité secrète');

        $this->actingAs($bde)->post(route('admin.moderation.approve', ['post', $post->id]))->assertRedirect();

        $post->refresh();
        $this->assertSame(Status::Approved, $post->status);
        $this->assertNotNull($post->published_at);
        $this->get(route('clubs.show', $club))->assertSee('Actualité secrète');
    }

    public function test_member_of_club_a_cannot_edit_post_of_club_b(): void
    {
        $clubA = $this->club('ais');
        $clubB = $this->club('hackademy');
        $memberA = $this->user('memberA@test.fr', Role::ClubMember, $clubA);
        $postB = $this->createPost($clubB, $memberA, Status::Draft, 'Post club B', 'Texas');

        $this->actingAs($memberA)
            ->put(route('manage.posts.update', $postB), ['title' => 'Pirate', 'body' => 'Modifié'])
            ->assertForbidden();

        $this->assertSame('Post club B', $postB->fresh()->title);
    }

    public function test_editing_approved_post_does_not_change_its_status(): void
    {
        $club = $this->club();
        $member = $this->user('member@test.fr', Role::ClubMember, $club);
        $post = $this->createPost($club, $member, Status::Approved, 'Déjà publié', 'Ancien texte');
        $publishedAt = $post->published_at;

        $this->actingAs($member)->put(route('manage.posts.update', $post), [
            'title' => 'Titre modifié',
            'body' => 'Nouveau texte',
        ])->assertRedirect(route('manage.posts.index'));

        $post->refresh();
        $this->assertSame(Status::Approved, $post->status);
        $this->assertSame('Titre modifié', $post->title);
        $this->assertTrue($post->published_at->equalTo($publishedAt));
    }
}
