<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Craft;
use App\Models\Item;
use Illuminate\Support\Collection;

class ItemData extends Data
{
    private Item $model;

    /**
     * @param Collection<SimpleEffectData> $effects
     * @param Collection<ItemConditionData> $conditions
     * @param CraftData $craft
     */
    public function __construct(
        public string $name,
        public string $code,
        public int $level,
        public string $type,
        public string $subtype,
        public string $description,
        public array|Collection $conditions,
        public bool $tradeable,
        public array|Collection $effects = [],
        public null|array|CraftData $craft = null,
    ) {
        $this->effects = SimpleEffectData::collection($effects);
        $this->conditions = ItemConditionData::collection($conditions);
        $this->craft = CraftData::from($craft);

        $this->createIfNotExists();
    }

    public function getModel(): Item
    {
        return $this->model;
    }

    private function createIfNotExists(): void
    {
        $this->model = Item::firstOrCreate(['code' => $this->code], [
            'name' => $this->name,
            'level' => $this->level,
            'type' => $this->type,
            'subtype' => $this->subtype,
            'description' => $this->description,
            'tradeable' => $this->tradeable,
        ]);

        if (
            $this->effects->isNotEmpty()
            && $this->model->effects()->doesntExist()
        ) {
            $this->effects->each(function (SimpleEffectData $effect) {
                $this->model->effects()->attach(
                    $effect->getModel(),
                    ['value' => $effect->value]
                );
            });
        }

        if (
            $this->conditions->isNotEmpty()
            && $this->model->conditions()->doesntExist()
        ) {
            $this->conditions->each(function (ItemConditionData $condition) {
                $this->model->conditions()->updateOrCreate([
                    'code' => $condition->code,
                ], [
                    'operator' => $condition->operator,
                    'value' => $condition->value,
                ]);
            });
        }

        if (! $this->craft) {
            return;
        }

        /** @var Craft */
        $craft = $this->model->craft()->updateOrCreate([
            'skill' => $this->craft->skill,
            'level' => $this->craft->level,
            'quantity' => $this->craft->quantity,
        ]);

        if ($craft->requiredItems()->count() === $this->craft->items->count()) {
            return;
        }

        $craft->requiredItems()->sync(
            $this->craft->items->mapWithKeys(
                fn (SimpleItemData $simpleItemData) => [
                    $simpleItemData->code => [
                        'quantity' => $simpleItemData->quantity,
                    ],
                ]
            )->all()
        );
    }
}
