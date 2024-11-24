<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Enums\SuitOfArmorParts;
use App\Jobs\CharacterJob;
use App\Models\Item;

class CollectSuitOfArmor extends CharacterJob
{
    protected Item $item;

    public function __construct(
        protected int $characterId,
        protected string $material,
        protected bool $equipImmediately = true,
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'material',
            'equipImmediately'
        );
    }

    protected function handleCharacter(): void
    {
        foreach (SuitOfArmorParts::cases() as $part) {
            $this->handlePart($part);
        }

        $this->log("all parts of armor {$this->material} collected");
    }

    private function handlePart(SuitOfArmorParts $part): void
    {
        $itemCode = $part->itemCode($this->material);

        if (
            $this->equipImmediately
            && $this->character->isEquipedWith($itemCode)
        ) {
            $this->log("already equiped with {$itemCode}");

            return;
        }

        if (
            ! $this->equipImmediately
            && $this->character->hasInInventory($itemCode)
        ) {
            $this->log("already has {$itemCode} in inventory");

            return;
        }

        $item = Item::findByCode($itemCode);

        if (! $item) {
            $this->fail("Item with code {$itemCode} does not exist");
        }

        if ($this->character->hasInInventory($item)) {
            $this->log("already has {$item->name} in inventory");

            if ($this->equipImmediately) {
                $this->log("equiping {$item->name}");
                $this->character->equip($item);
            }
        }

        if (! $this->character->hasSkillLevel($item->craft)) {
            $this->log("missing skill level to craft {$item->name}");

            return;
        }

        $this->log("collecting {$item->name}");

        $this->dispatchWithComeback(
            new CollectRawMaterialsToCraft($this->characterId, $item->id)
        );

        // stop loop here and craft the item
        $this->end();
    }
}
