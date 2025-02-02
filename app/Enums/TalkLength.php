<?php

namespace App\Enums;

enum TalkLength: string
{

    case LIGHTNING = 'Lightning - 15 min';
    case NORMAL = 'Normal - 30 min';
    case KEYNOTE = 'Keynote';
}
