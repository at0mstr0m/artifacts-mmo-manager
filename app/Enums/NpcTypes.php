<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum NpcTypes: string
{
    use EnumUtils;

    case MERCHANT = 'merchant';
    case TRADER = 'trader';
}
