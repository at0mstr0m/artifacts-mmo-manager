<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

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
        $this->log('must determine location to craft item');
        $this->item = Item::find($this->itemId);

        $craft = $this->item->craft;

        if (! $this->character->hasSkillLevel($craft)) {
            $this->fail(
                $this->character->name
                . ' does not have required skill level'
                . $craft->level
                . ' to craft '
                . $this->item->name
            );

            return;
        }

        $workshop = Map::query()
            ->where('content_type', 'workshop')
            ->where('content_code', $craft->skill)
            ->first();

        if (! $this->character->isAt($workshop)) {
            $this->log('is not at workshop');
            $moveData = $this->character->moveTo($workshop);
            $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

            return;
        }
        $this->log(
            'is at workshop and will now craft '
            . $this->quantity
            . ' units of '
            . $this->item->name
        );
        $craftingData = $this->character->craft($this->item, $this->quantity);
        $this->dispatchNextJob()?->delay($craftingData->cooldown->expiresAt);
    }
}
