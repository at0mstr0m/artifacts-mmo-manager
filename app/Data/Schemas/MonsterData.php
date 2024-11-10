<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;
use App\Models\Monster;
use Illuminate\Support\Collection;

class MonsterData extends Data
{
    /**
     * @param Collection<DropData> $drops
     */
    public function __construct(
        public string $name,
        public string $code,
        public int $level,
        public int $hp,
        public int $attackFire,
        public int $attackEarth,
        public int $attackWater,
        public int $attackAir,
        public int $resFire,
        public int $resEarth,
        public int $resWater,
        public int $resAir,
        public int $minGold,
        public int $maxGold,
        public array|Collection $drops,
    ) {
        $this->drops = DropData::collection($drops);

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        /** @var Monster */
        $monster = Monster::firstOrCreate(['code' => $this->code], [
            'name' => $this->name,
            'level' => $this->level,
            'hp' => $this->hp,
            'attack_fire' => $this->attackFire,
            'attack_earth' => $this->attackEarth,
            'attack_water' => $this->attackWater,
            'attack_air' => $this->attackAir,
            'res_fire' => $this->resFire,
            'res_earth' => $this->resEarth,
            'res_water' => $this->resWater,
            'res_air' => $this->resAir,
            'min_gold' => $this->minGold,
            'max_gold' => $this->maxGold,
        ]);

        if (! $monster->wasRecentlyCreated) {
            return;
        }

        $this->drops->each(
            fn (DropData $drop) => $monster
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
