<?php

namespace App\Enums;

/** Les neuf filières de l'ENSAJ. */
enum FieldOfStudy: string
{
    case Cp1 = 'cp1';
    case Cp2 = 'cp2';
    case Isic = 'isic';
    case Iite = 'iite';
    case Ccn = 'ccn';
    case Gc = 'gc';
    case Gm = 'gm';
    case Gee = 'gee';
    case Gi = 'gi';

    public function label(): string
    {
        return strtoupper($this->value);
    }
}
