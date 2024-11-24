<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\TaskTypes;

class NewTaskData extends Data
{
    public int $rewardedCoins;

    /**
     * @param TaskTypes $type
     */
    public function __construct(
        public string $code,
        public string|TaskTypes $type,
        public int $total,
        array $rewards,
    ) {
        $this->type = TaskTypes::fromValue($type);
        $this->rewardedCoins = data_get($rewards, 'items.0.quantity');
    }
}
