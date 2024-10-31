<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Craft;
use App\Models\Effect;
use App\Models\Item;
use App\Services\ArtifactsService;
use Illuminate\Support\Collection;

class ItemData extends Data
{
    private Item $model;

    /**
     * @param  Collection<ItemEffectData>  $effects
     */
    public function __construct(
        public string $name,
        public string $code,
        public int $level,
        public string $type,
        public string $subtype,
        public string $description,
        public array|Collection $effects = [],
        public null|array|CraftData $craft = null,
    ) {
        $this->effects = ItemEffectData::collection($effects);
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
        ]);

        if (! $this->model->wasRecentlyCreated) {
            return;
        }

        $this->effects?->each(function (ItemEffectData $effect) {
            $this->model->effects()->attach(Effect::firstOrCreate([
                'name' => $effect->name,
                'value' => $effect->value,
            ]));
        });

        if (! $this->craft) {
            return;
        }

        $craft = Craft::firstOrCreate([
            'skill' => $this->craft->skill,
            'level' => $this->craft->level,
            'quantity' => $this->craft->quantity,
        ]);

        $this->craft->items->each(
            function (SimpleItemData $simpleItemData) use ($craft) {
                $requiredItem = Item::firstWhere('code', $simpleItemData->code)
                    ?? app(ArtifactsService::class)
                        ->getItem($simpleItemData->code)
                        ->item
                        ->getModel();

                $craft->items()->attach(
                    $requiredItem,
                    ['quantity' => $simpleItemData->quantity]
                );
            }
        );
    }
}
