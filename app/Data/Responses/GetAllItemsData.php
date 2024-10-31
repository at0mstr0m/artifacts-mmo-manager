<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Schemas\ItemData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class GetAllItemsData extends Data
{
    /**
     * @param  Collection<ItemData>  $items
     */
    public function __construct(
        public Collection $items,
    ) {}
}
