<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum CooldownReasons: string
{
    use EnumUtils;
    
    case MOVEMENT = 'movement';
    case FIGHT = 'fight';
    case CRAFTING = 'crafting';
    case GATHERING = 'gathering';
    case BUY_GE = 'buy_ge';
    case SELL_GE = 'sell_ge';
    case DELETE_ITEM = 'delete_item';
    case DEPOSIT_BANK = 'deposit_bank';
    case WITHDRAW_BANK = 'withdraw_bank';
    case EQUIP = 'equip';
    case UNEQUIP = 'unequip';
    case TASK = 'task';
    case RECYCLING = 'recycling';
}
