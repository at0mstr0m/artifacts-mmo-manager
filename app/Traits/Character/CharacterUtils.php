<?php

declare(strict_types=1);

namespace App\Traits\Character;

use App\Data\Schemas\SimpleItemData;
use App\Enums\Skills;
use App\Models\Character;
use App\Models\Craft;
use App\Models\Item;
use App\Models\ItemCondition;
use App\Models\Map;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @mixin Character
 */
trait CharacterUtils
{
    public function isAt(int|Map $x, ?int $y = null): bool
    {
        if ($x instanceof Map) {
            $y = $x->y;
            $x = $x->x;
        }

        return $this->x === $x && $this->y === $y;
    }

    public function hasInInventory(
        int|Item|SimpleItemData|string $item,
        int $quantity = 1
    ): bool {
        switch (true) {
            case is_int($item):
                $item = Item::find($item)->code;
                break;
            case $item instanceof Item:
                $item = $item->code;
                break;
            case $item instanceof SimpleItemData:
                $quantity = $item->quantity;
                $item = $item->code;
                break;
        }

        return $this
            ->inventoryItems()
            ->where('code', $item)
            ->where('quantity', '>=', $quantity)
            ->exists();
    }

    public function countInInventory(int|Item|SimpleItemData|string $item): int
    {
        switch (true) {
            case is_int($item):
                $item = Item::find($item)->code;
                break;
            case $item instanceof Item:
            case $item instanceof SimpleItemData:
                $item = $item->code;
                break;
        }

        return (int) $this
            ->inventoryItems()
            ->where('code', $item)
            ->sum('quantity');
    }

    public function hasSkillLevel(
        Craft|Skills|string $skill,
        ?int $level = null
    ): bool {
        switch (true) {
            case $skill instanceof Craft:
                $level = $skill->level;
                $skill = $skill->skill->value;
                break;
            case $skill instanceof Skills:
                $skill = $skill->value;
                break;
        }

        return $this->{$skill . '_level'} >= $level;
    }

    public function getSkillLevel(Skills|string $skill): int
    {
        $skill = is_string($skill) ? $skill : $skill->value;

        return $this->{$skill . '_level'};
    }

    public function getSkillLevels(): Collection
    {
        return collect(Skills::values())
            ->mapWithKeys(fn (string $skill): array => [
                $skill => $this->getSkillLevel($skill),
            ]);
    }

    public function isEquipedWith(Item|string $itemCode): bool
    {
        if ($itemCode instanceof Item) {
            $itemCode = $itemCode->code;
        }

        return $this->getSlots()->contains($itemCode);
    }

    public function slotIsOccupied(string $slot): bool
    {
        return (bool) $this->{$slot};
    }

    public function getSlots(): Collection
    {
        return collect($this->getAttributes())->filter(
            function (mixed $value, string $key): bool {
                return Str::endsWith($key, '_slot');
            }
        );
    }

    public function getSlotNames(): Collection
    {
        return $this->getSlots()->keys();
    }

    public function inventoryIsFull(): bool
    {
        return $this->inventory_count === $this->inventory_max_items
            || $this->inventoryItems()->pluck('code')->filter()->count() >= 20;
    }

    public function isOnlyLoadedWith(int|Item|SimpleItemData|string $item): bool
    {
        return $this->countInInventory($item) === $this->inventory_count;
    }

    public function canUseItem(Item|string $item): bool
    {
        if (is_string($item)) {
            $item = Item::findByCode($item);
        }

        $conditions = $item->conditions;

        if ($conditions->isEmpty()) {
            return true;
        }

        return $conditions->every(
            fn (ItemCondition $condition) => $condition
                ->operator
                ->compare(
                    $this->getAttribute($condition->attribute),
                    $condition->value
                )
        );
    }
}
