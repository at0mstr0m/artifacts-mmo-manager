<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum EffectSubTypes: string
{
    use EnumUtils;

    case STAT = 'stat';
    case OTHER = 'other';
    case HEAL = 'heal';
    case BUFF = 'buff';
    case DEBUFF = 'debuff';
    case SPECIAL = 'special';
    case GATHERING = 'gathering';
    case TELEPORT = 'teleport';
    case GOLD = 'gold';
}
