<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum AchievementTypes: string
{
    use EnumUtils;

    case COMBAT_KILL = 'combat_kill';
    case COMBAT_DROP = 'combat_drop';
    case COMBAT_LEVEL = 'combat_level';
    case GATHERING = 'gathering';
    case CRAFTING = 'crafting';
    case RECYCLING = 'recycling';
    case TASK = 'task';
    case USE = 'use';
    case OTHER = 'other';
}
