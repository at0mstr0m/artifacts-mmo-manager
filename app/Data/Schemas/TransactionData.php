<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;

class TransactionData extends Data
{
    public string $identifier;

    public Item $item;

    public function __construct(
        string $id,
        string $code,
        public int $quantity,
        public int $price,
        public int $totalPrice,
    ) {
        $this->identifier = $id;
        $this->item = Item::firstWhere('code', $code);

        $this->item->transactions()->create([
            'identifier' => $this->identifier,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total_price' => $this->totalPrice,
        ]);
    }
}
