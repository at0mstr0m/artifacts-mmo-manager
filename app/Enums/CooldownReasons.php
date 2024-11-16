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
    case CANCEL_GE = 'cancel_ge';
    case DELETE_ITEM = 'delete_item';
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case DEPOSIT_GOLD = 'deposit_gold';
    case WITHDRAW_GOLD = 'withdraw_gold';
    case EQUIP = 'equip';
    case UNEQUIP = 'unequip';
    case TASK = 'task';
    case RECYCLING = 'recycling';
    case REST = 'rest';
    case USE = 'use';
    case BUY_BANK_EXPANSION = 'buy_bank_expansion';
}
