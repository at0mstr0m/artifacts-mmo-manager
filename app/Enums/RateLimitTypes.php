<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum RateLimitTypes: string
{
    use EnumUtils;

    case ACCOUNT_CREATION = 'Account creation';
    case TOKEN = 'Token';
    case DATA = 'Data';
    case ACTIONS = 'Actions';

    public function getLimits(): array
    {
        return match ($this) {
            self::ACCOUNT_CREATION => ['hour' => 50],
            self::TOKEN => ['hour' => 50],
            self::DATA => ['second' => 16, 'minute' => 200, 'hour' => 7200],
            self::ACTIONS => ['second' => 5, 'minute' => 200, 'hour' => 7200],
        };
    }
}
