<?php

namespace Tests\Feature;

use App\Enums\MessageType;
use App\Enums\Role;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_initiator_and_members_of_the_club_can_open_a_thread(): void
    {
        $clubA = $this->club('ais');
        $clubB = $this->club('hackademy');
        $student1 = $this->user('s1@test.fr');
        $student2 = $this->user('s2@test.fr');
        $memberA = $this->user('mA@test.fr', Role::ClubMember, $clubA);
        $memberB = $this->user('mB@test.fr', Role::ClubMember, $clubB);
        $bde = $this->user('bde@test.fr', Role::Bde);

        $conversation = Conversation::create([
            'club_id' => $clubA->id,
            'user_id' => $student1->id,
            'last_message_at' => now(),
        ]);
        $message = new Message(['body' => 'Bonjour club A']);
        $message->conversation_id = $conversation->id;
        $message->author_id = $student1->id;
        $message->type = MessageType::Question;
        $message->save();

        $this->actingAs($student1)->get(route('conversations.show', $conversation))->assertOk();
        $this->actingAs($student2)->get(route('conversations.show', $conversation))->assertForbidden();
        $this->actingAs($memberA)->get(route('conversations.show', $conversation))->assertOk();
        $this->actingAs($memberB)->get(route('conversations.show', $conversation))->assertForbidden();
        $this->actingAs($bde)->get(route('conversations.show', $conversation))->assertForbidden();
    }

    public function test_member_cannot_contact_his_own_club(): void
    {
        $club = $this->club();
        $member = $this->user('member@test.fr', Role::ClubMember, $club);

        $this->actingAs($member)->get(route('conversations.contact', $club))->assertForbidden();
        $this->actingAs($member)->post(route('conversations.store', $club), [
            'type' => 'question',
            'body' => 'Bonjour',
        ])->assertForbidden();
    }
}
