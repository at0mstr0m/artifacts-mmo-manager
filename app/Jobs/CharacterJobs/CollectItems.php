<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\Schemas\SimpleItemData;
use App\Jobs\CharacterJob;
use App\Models\Drop;
use App\Models\Item;
use App\Models\Monster;
use App\Models\Resource;
use Database\Seeders\BankItemSeeder;
use Illuminate\Support\Collection;

class CollectItems extends CharacterJob
{
    private SimpleItemData $nextItemData;

    private Item $nextItem;

    /**
     * @param Collection<SimpleItemData> $items
     */
    public function __construct(
        protected int $characterId,
        protected Collection $items,
    ) {
        $this->constructorArguments = compact('characterId', 'items');
    }

    protected function handleCharacter(): void
    {
        $this->checkAllItemsCollected();

        $this->determineNextItem();

        $this->collectNextItemFromBank();

        $this->collectNextItemFromDrop();

        $this->collectNextItemFromCrafting();
    }

    private function checkAllItemsCollected(): void
    {
        if ($this->items->every(
            fn (SimpleItemData $item) => $this->character->hasInInventory($item)
        )) {
            $this->log('All items collected');
            $this->dispatchNextJob();

            $this->end();
        }

        $this->log('Not all Items collected yet.');
    }

    private function determineNextItem(): void
    {
        $this->nextItemData = $this->items->firstWhere(
            fn (SimpleItemData $item) => ! $this->character->hasInInventory($item)
        );

        $this->nextItem = $this->nextItemData->getModel();
    }

    private function collectNextItemFromBank(): void
    {
        (new BankItemSeeder())->run();
        $depositedQuantity = $this->nextItem->refresh()->deposited;

        $this->log(
            'Item '
            . $this->nextItem->name
            . ' has '
            . $depositedQuantity
            . ' units deposited in bank'
        );

        if (! $depositedQuantity) {
            return;
        }

        $this->dispatchWithComeback(
            new WithdrawFromBank(
                $this->characterId,
                $this->nextItem->code,
                min($this->nextItemData->quantity, $depositedQuantity)
            )
        );

        $this->end();
    }

    private function collectNextItemFromDrop(): void
    {
        $this->log(
            'Must collect '
            . $this->nextItemData->quantity
            . ' units of '
            . $this->nextItem->name
        );
        $currentQuantity = $this->character->countInInventory($this->nextItem);
        $this->log("currently has {$currentQuantity} units");

        /** @var Drop */
        $drop = $this->nextItem->drops()->orderBy('rate')->first();

        $job = match ($drop?->source_type) {
            Monster::class => new FightMonsterDrop(
                $this->characterId,
                $drop->source_id,
                $this->nextItemData,
            ),
            Resource::class => new GatherItem(
                $this->characterId,
                $this->nextItem->id,
                $this->nextItemData->quantity,
            ),
            default => null,
        };

        if ($job) {
            $this->dispatchWithComeback($job);

            $this->end();
        }

        $this->log(
            'Cannot obtain '
            . $this->nextItem->name
            . ' as drop from any source.'
        );
    }

    private function collectNextItemFromCrafting(): void
    {
        if ($this->nextItem->craft()->doesntExist()) {
            $this->fail(
                'Item '
                . $this->nextItem->name
                . ' can neither be obtained as drop from source nor be crafted'
            );
        }

        $this->log("Item {$this->nextItem->name} can be crafted");
        $this->dispatchWithComeback(
            new CollectRawMaterialsToCraft(
                $this->characterId,
                $this->nextItem->id,
                $this->nextItemData->quantity
            )
        );
    }
}
