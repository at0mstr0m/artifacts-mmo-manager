<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class TransactionData extends Data
{
    public string $identifier;

    public Carbon $placedAt;

    public Item $item;

    public function __construct(
        string $id,
        string $code,
        string $createdAt,
        public int $quantity,
        public int $price,
        public int $totalPrice,
        public int $tax,
    ) {
        $this->identifier = $id;
        $this->item = Item::firstWhere('code', $code);
        $this->placedAt = Carbon::parse($createdAt);
    }

    public function createModel(): Transaction
    {
        return $this->item->transactions()->create([
            'identifier' => $this->identifier,
            'placed_at' => $this->placedAt,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total_price' => $this->totalPrice,
            'tax' => $this->tax,
        ]);
    }
}
