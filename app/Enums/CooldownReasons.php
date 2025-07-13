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
    case BUY_NPC = 'buy_npc';
    case SELL_NPC = 'sell_npc';
    case CANCEL_GE = 'cancel_ge';
    case DELETE_ITEM = 'delete_item';
    case DEPOSIT_ITEM = 'deposit_item';
    case WITHDRAW_ITEM = 'withdraw_item';
    case DEPOSIT_GOLD = 'deposit_gold';
    case WITHDRAW_GOLD = 'withdraw_gold';
    case EQUIP = 'equip';
    case UNEQUIP = 'unequip';
    case TASK = 'task';
    case CHRISTMAS_EXCHANGE = 'christmas_exchange';
    case RECYCLING = 'recycling';
    case REST = 'rest';
    case USE = 'use';
    case BUY_BANK_EXPANSION = 'buy_bank_expansion';
    case GIVE_ITEM = 'give_item';
    case GIVE_GOLD = 'give_gold';
    case CHANGE_SKIN = 'change_skin';
    case RENAME = 'rename';
}
