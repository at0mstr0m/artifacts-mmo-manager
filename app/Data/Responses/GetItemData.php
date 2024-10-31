<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\GrandExchangeItemData;
use App\Data\Schemas\ItemData;

class GetItemData extends Data
{
    public function __construct(
        public array|ItemData $item,
        public null|array|GrandExchangeItemData $ge,
    ) {
        $this->item = ItemData::from($item);
        $this->ge = GrandExchangeItemData::from($ge);
    }
}
