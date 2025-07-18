<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\Schemas\SimpleItemData;
use App\Jobs\CharacterJob;
use App\Models\Item;
use App\Models\Map;

class CraftItem extends CharacterJob
{
    protected Item $item;

    public function __construct(
        protected int $characterId,
        protected int $itemId,
        protected int $quantity = 1,
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'itemId',
            'quantity'
        );
    }

    protected function handleCharacter(): void
    {
        $this->ensureHasSkillLevel();

        $this->handleFullInventory();

        $this->ensureIsAtWorkshop();

        $this->craftItem();
    }

    private function ensureHasSkillLevel(): void
    {
        $this->log('Must ensure has skill level');
        $this->item = Item::find($this->itemId);
        $craft = $this->item->craft;

        if ($this->character->hasSkillLevel($craft)) {
            $this->log(
                'has required skill level '
                . $craft->level
                . ' to craft '
                . $this->item->name
            );

            return;
        }

        $this->log(
            'Character does not have required skill level '
            . $craft->level
            . ' to craft '
            . $this->item->name
        );

        $this->dispatchWithComeback(
            new CollectSkillXp(
                $this->character->id,
                $craft->skill,
                $craft->level
            )
        );

        $this->end();
    }

    private function handleFullInventory(): void
    {
        if (! $this->character->inventoryIsFull()) {
            return;
        }

        $this->log('Inventory is full, emptying it now');

        $keep = collect([new SimpleItemData($this->item->code, $this->quantity)]);
        $this->item
            ?->craft
            ?->requiredItems
            ?->each(fn (Item $item) => $keep->push(
                new SimpleItemData(
                    $item->code,
                    $item->pivot->quantity * $this->quantity
                )
            ));

        $this->dispatchWithComeback(
            new EmptyInventory($this->character->id, $keep)
        );

        $this->end();
    }

    private function ensureIsAtWorkshop(): void
    {
        $workshop = Map::query()
            ->where('content_type', 'workshop')
            ->where('content_code', $this->item->craft->skill)
            ->first();

        if (! $this->character->isAt($workshop)) {
            $this->log('is not at workshop');
            $moveData = $this->character->moveTo($workshop);
            $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

            $this->end();
        }

        $this->log(
            'is at workshop and will now craft '
            . $this->quantity
            . ' units of '
            . $this->item->name
        );
    }

    private function craftItem(): void
    {
        $craftingData = $this->character->craft($this->item, $this->quantity);
        $this->dispatchNextJob()?->delay($craftingData->cooldown->expiresAt);
    }
}
