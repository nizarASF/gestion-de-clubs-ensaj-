<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isClubMember() || $user->isBde();
    }

    public function view(User $user, Media $media): bool
    {
        return $user->isBde() || ($user->isClubMember() && $user->club_id === $media->club_id);
    }

    public function create(User $user): bool
    {
        return $user->isClubMember();
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->isBde();
    }
}
