<?php

namespace App\Enums;

enum MessageType: string
{
    case Question = 'question';
    case Suggestion = 'suggestion';

    public function label(): string
    {
        return match ($this) {
            self::Question => 'Question',
            self::Suggestion => 'Suggestion',
        };
    }
}
