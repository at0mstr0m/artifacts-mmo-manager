<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;
use Illuminate\Support\Carbon;

class HistoricSellOrderData extends Data
{
    public string $identifier;

    public Item $item;

    public int $totalPrice;

    public function __construct(
        string $orderId,
        public string $seller,
        public string $buyer,
        string $code,
        public int $quantity,
        public int $price,
        public Carbon|string $soldAt,
    ) {
        $this->identifier = $orderId;
        $this->item = Item::firstWhere('code', $code);
        $this->soldAt = Carbon::parse($soldAt);
        $this->totalPrice = $this->quantity * $this->price;

        $this->item->sellOrders()->updateOrCreate([
            'identifier' => $this->identifier,
        ], [
            'seller' => $this->seller,
            'buyer' => $this->buyer,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total_price' => $this->totalPrice,
            'sold_at' => $this->soldAt,
        ]);
    }
}
