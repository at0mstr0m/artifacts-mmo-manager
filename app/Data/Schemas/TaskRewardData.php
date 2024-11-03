<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\TaskReward;

class TaskRewardData extends Data
{
    public int $rewardedCoins;

    public function __construct(
        public string $code,
        public int $minQuantity,
        public int $maxQuantity,
        public float $odds,
    ) {
        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        TaskReward::firstOrCreate([
            'code' => $this->code,
            'min_quantity' => $this->minQuantity,
            'max_quantity' => $this->maxQuantity,
            'odds' => $this->odds,
        ]);
    }
}
