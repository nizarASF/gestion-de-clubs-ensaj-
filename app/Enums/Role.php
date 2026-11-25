<?php

namespace App\Enums;

/** Rôles possibles d'un compte (voir section 2 du ROADMAP). */
enum Role: string
{
    case Student = 'student';
    case ClubMember = 'club_member';
    case Bde = 'bde';

    public function label(): string
    {
        return match ($this) {
            self::Student => 'Étudiant',
            self::ClubMember => 'Membre du club',
            self::Bde => 'BDE',
        };
    }
}
