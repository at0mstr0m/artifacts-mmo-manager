<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Models\Effect;
use App\Models\Item;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ItemData extends Data
{
    /**
     * @param  Collection<ItemEffectData>  $effects
     */
    public function __construct(
        public ?string $name,
        public ?string $code,
        public ?int $level,
        public ?string $type,
        public ?string $subtype,
        public ?string $description,
        public ?Collection $effects,
        public ?CraftData $craft = null,
    ) {
        $this->createIfNotExists();
        Item::firstOrCreate([
            'name' => $this->name,
            'code' => $this->code,
            'level' => $this->level,
            'type' => $this->type,
            'subtype' => $this->subtype,
            'description' => $this->description,
        ]);
    }

    private function createIfNotExists(): void
    {
        $item = Item::firstOrCreate([
            'name' => $this->name,
            'code' => $this->code,
            'level' => $this->level,
            'type' => $this->type,
            'subtype' => $this->subtype,
            'description' => $this->description,
        ]);

        if (! $item->wasRecentlyCreated) {
            return;
        }

        $this->effects->each(function (ItemEffectData $effect) use ($item) {
            $item->effects()->attach(Effect::firstOrCreate([
                'name' => $effect->name,
                'value' => $effect->value,
            ]));
        });
    }
}
