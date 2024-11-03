<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum TaskTypes: string
{
    use EnumUtils;

    case MONSTERS = 'monsters';
    case ITEMS = 'items';
}
