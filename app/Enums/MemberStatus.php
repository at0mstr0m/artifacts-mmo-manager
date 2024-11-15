<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum MemberStatus: string
{
    use EnumUtils;

    case STANDARD = 'standard';
    case FOUNDER = 'founder';
    case GOLD_FOUNDER = 'gold_founder';
    case VIP_FOUNDER = 'vip_founder';
}
