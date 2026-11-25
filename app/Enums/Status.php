<?php

namespace App\Enums;

/** Statuts partagés par les inscriptions, posts, fiches et médias. */
enum Status: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    /** Libellé français affiché dans les badges. */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Brouillon',
            self::Pending => 'En attente',
            self::Approved => 'Validé',
            self::Rejected => 'Refusé',
        };
    }

    /** Classe CSS du badge (définie dans public/css/app.css). */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft => 'badge--draft',
            self::Pending => 'badge--pending',
            self::Approved => 'badge--approved',
            self::Rejected => 'badge--rejected',
        };
    }
}
