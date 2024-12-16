<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\AchievementTypes;
use App\Models\Achievement;
use Illuminate\Support\Carbon;

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
        public ?int $current = null,
        public null|Carbon|string $completedAt = null,
    ) {
        $this->type = AchievementTypes::fromValue($type);
        $this->rewardedGold = $rewards['gold'];
        $this->completedAt = $completedAt ? Carbon::parse($completedAt) : null;

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Achievement::updateOrCreate([
            'code' => $this->code,
        ], [
            'name' => $this->name,
            'description' => $this->description,
            'points' => $this->points,
            'type' => $this->type,
            'total' => $this->total,
            'target' => $this->target,
            'rewarded_gold' => $this->rewardedGold,
            ...is_null($this->current) ? [] : ['current' => $this->current],
            ...is_null($this->completedAt)
                ? []
                : ['completed_at' => $this->completedAt],
        ]);
    }
}
