<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\ItemData;
use Illuminate\Support\Collection;

class GetAllItemsData extends Data
{
    /**
     * @param  Collection<ItemData>  $items
     */
    public function __construct(
        public Collection $items,
    ) {
        $this->items = ItemData::collection($items);
    }
}
