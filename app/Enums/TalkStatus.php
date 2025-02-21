<?php

namespace App\Enums;

use Filament\Forms\Set;

enum TalkStatus: string
{
    case SUBMITTED = 'Submitted';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';

    public function getColor(): string
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::SUBMITTED => 'primay',
            self::REJECTED => 'danger'
        };
    }
}
