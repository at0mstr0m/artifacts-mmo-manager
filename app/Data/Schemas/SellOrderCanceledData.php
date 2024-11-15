<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;
use App\Models\SellOrder;
use Illuminate\Support\Carbon;

class SellOrderCanceledData extends Data
{
    public string $identifier;

    public Item $item;

    public Carbon $placedAt;

    public function __construct(
        string $id,
        string $createdAt,
        string $code,
        public int $quantity,
        public int $price,
        public int $totalPrice,
        public int $tax,
    ) {
        $this->identifier = $id;
        $this->item = Item::firstWhere('code', $code);
        $this->placedAt = Carbon::parse($createdAt);

        SellOrder::where('identifier', $id)->delete();
    }
}
