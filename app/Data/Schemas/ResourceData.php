<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\Skills;
use App\Models\Item;
use App\Models\Resource;
use Illuminate\Support\Collection;

class ResourceData extends Data
{
    /**
     * @param  Collection<DropData>  $drops
     */
    public function __construct(
        public string $name,
        public string $code,
        public Skills|string $skill,
        public int $level,
        public array|Collection $drops,
    ) {
        $this->skill = Skills::fromValue($skill);
        $this->drops = DropData::collection($drops);

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        /** @var App\Models\Resource */
        $resource = Resource::firstOrCreate(['code' => $this->code], [
            'name' => $this->name,
            'level' => $this->level,
            'skill' => $this->skill,
        ]);

        if (! $resource->wasRecentlyCreated) {
            return;
        }

        $this->drops->each(
            fn (DropData $drop) => $resource
                ->drops()
                ->make([
                    'rate' => $drop->rate,
                    'min_quantity' => $drop->minQuantity,
                    'max_quantity' => $drop->maxQuantity,
                ])
                ->item()
                ->associate(Item::firstWhere('code', $drop->code))
                ->saveOrFail()
        );
    }
}
