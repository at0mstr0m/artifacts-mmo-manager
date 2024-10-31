<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum Skills: string
{
    use EnumUtils;

    case WEAPON_CRAFTING = 'weaponcrafting';
    case GEAR_CRAFTING = 'gearcrafting';
    case JEWELRY_CRAFTING = 'jewelrycrafting';
    case COOKING = 'cooking';
    case WOOD_CUTTING = 'woodcutting';
    case MINING = 'mining';
}
