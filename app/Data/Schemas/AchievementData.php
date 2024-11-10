<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\AchievementTypes;
use App\Models\Achievement;

class AchievementData extends Data
{
    public int $rewardedGold;

    /**
     * @param AchievementTypes $type
     */
    public function __construct(
        public string $name,
        public string $code,
        public string $description,
        public int $points,
        public AchievementTypes|string $type,
        public int $total,
        array $rewards,
        public ?string $target = null,
    ) {
        $this->type = AchievementTypes::fromValue($type);
        $this->rewardedGold = $rewards['gold'];
        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Achievement::firstOrCreate([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'points' => $this->points,
            'type' => $this->type,
            'total' => $this->total,
            'target' => $this->target,
            'rewarded_gold' => $this->rewardedGold,
        ]);
    }
}
