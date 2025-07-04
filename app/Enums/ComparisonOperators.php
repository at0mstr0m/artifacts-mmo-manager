<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum ComparisonOperators: string
{
    use EnumUtils;

    case EQUALS = 'eq';
    case NOT_EQUALS = 'ne';
    case GREATER_THAN = 'gt';
    case LESS_THAN = 'lt';
}
