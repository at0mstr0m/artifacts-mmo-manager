<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\Skills;
use App\Enums\TaskTypes;
use App\Models\Task;

class TaskData extends Data
{
    public int $rewardedCoins;

    /**
     * @param TaskTypes $type
     * @param ?Skills $skill
     */
    public function __construct(
        public string $code,
        public int $level,
        public string|TaskTypes $type,
        public int $minQuantity,
        public int $maxQuantity,
        array $rewards,
        public null|Skills|string $skill = null,
    ) {
        $this->type = TaskTypes::fromValue($type);
        $this->skill = $this->skill ? Skills::fromValue($skill) : null;
        $this->rewardedCoins = data_get($rewards, 'items.0.quantity');

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Task::firstOrCreate([
            'code' => $this->code,
            'level' => $this->level,
            'type' => $this->type,
            'min_quantity' => $this->minQuantity,
            'max_quantity' => $this->maxQuantity,
            'skill' => $this->skill,
            'rewarded_coins' => $this->rewardedCoins,
        ]);
    }
}
