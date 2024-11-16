<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum CharacterSkins: string
{
    use EnumUtils;

    case MEN1 = 'men1';
    case MEN2 = 'men2';
    case MEN3 = 'men3';
    case WOMEN1 = 'women1';
    case WOMEN2 = 'women2';
    case WOMEN3 = 'women3';
}
