<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Schemas\GrandExchangeItemData;
use App\Models\GrandExchangeItem;
use App\Services\ArtifactsService;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateGrandExchangeAction
{
    use AsAction;

    public function handle()
    {
        app(ArtifactsService::class)
            ->getAllGeItem(all: true)
            ->each(function (GrandExchangeItemData $item) {
                GrandExchangeItem::updateOrCreate([
                    'code' => $item->code,
                ], [
                    'stock' => $item->stock,
                    'sell_price' => $item->sellPrice,
                    'buy_price' => $item->buyPrice,
                    'max_quantity' => $item->maxQuantity,
                ]);
            });
    }
}
