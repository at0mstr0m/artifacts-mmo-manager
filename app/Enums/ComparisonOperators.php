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

    public function compare(int $a, int $b): bool
    {
        return match ($this) {
            self::EQUALS => $a === $b,
            self::NOT_EQUALS => $a !== $b,
            self::GREATER_THAN => $a > $b,
            self::LESS_THAN => $a < $b,
        };
    }
}
