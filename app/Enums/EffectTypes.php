<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum EffectTypes: string
{
    use EnumUtils;

    case EQUIPMENT = 'equipment';
    case CONSUMABLE = 'consumable';
    case COMBAT = 'combat';
    case ITEM = 'item';
}
