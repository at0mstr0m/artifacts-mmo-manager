<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum FightResults: string
{
    use EnumUtils;

    case WIN = 'win';
    case LOSE = 'lose';
}
