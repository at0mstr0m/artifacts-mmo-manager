<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;
use Illuminate\Support\Carbon;

class SellOrderData extends Data
{
    public string $identifier;

    public Item $item;

    public Carbon $placedAt;

    public function __construct(
        string $id,
        public string $seller,
        string $code,
        public int $quantity,
        public int $price,
        string $createdAt,
        public int $totalPrice = 0,
    ) {
        $this->identifier = $id;
        $this->item = Item::firstWhere('code', $code);
        $this->placedAt = Carbon::parse($createdAt);

        $this->item->sellOrders()->updateOrCreate([
            'identifier' => $this->identifier,
        ], [
            'seller' => $this->seller,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'placed_at' => $this->placedAt,
            'total_price' => $this->totalPrice,
        ]);
    }
}
