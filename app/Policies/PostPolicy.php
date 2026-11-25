<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isClubMember() || $user->isBde();
    }

    public function view(User $user, Post $post): bool
    {
        return $user->isBde() || ($user->isClubMember() && $user->club_id === $post->club_id);
    }

    /** Un membre modifie tous les posts de son club, pas seulement les siens. */
    public function create(User $user): bool
    {
        return $user->isClubMember();
    }

    public function update(User $user, Post $post): bool
    {
        return $user->isClubMember() && $user->club_id === $post->club_id;
    }

    public function submit(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
