<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isClubMember() || $user->isBde();
    }

    public function view(User $user, TeamMember $member): bool
    {
        return $user->isBde() || ($user->isClubMember() && $user->club_id === $member->club_id);
    }

    public function create(User $user): bool
    {
        return $user->isClubMember();
    }

    public function update(User $user, TeamMember $member): bool
    {
        return $user->isClubMember() && $user->club_id === $member->club_id;
    }
}
