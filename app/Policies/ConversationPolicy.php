<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /** Accès au fil : initiateur ou membre du club concerné. Le BDE est initiateur uniquement. */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->isReadableBy($user);
    }

    public function send(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }

    public function contact(User $user, Club $club): bool
    {
        return ! ($user->isClubMember() && $user->club_id === $club->id);
    }
}
