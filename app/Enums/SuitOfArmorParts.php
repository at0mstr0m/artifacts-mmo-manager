<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum SuitOfArmorParts: string
{
    use EnumUtils;

    case DAGGER = 'dagger';
    case BOOTS = 'boots';
    case HELMET = 'helmet';
    case RING = 'ring';
    case LEGS_ARMOR = 'legs_armor';
    case ARMOR = 'armor';

    public function itemCode(string $material): string
    {
        return $material . '_' . $this->value;
    }
}
