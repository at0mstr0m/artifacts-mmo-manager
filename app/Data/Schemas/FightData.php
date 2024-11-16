<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\FightResults;
use Illuminate\Support\Collection;

class FightData extends Data
{
    /**
     * @param Collection<DropData> $drops
     * @param BlockedHitsData $monsterBlockedHits
     * @param BlockedHitsData $playerBlockedHits
     * @param array<string> $logs
     * @param FightResults $result
     */
    public function __construct(
        public int $xp,
        public int $gold,
        public array|Collection $drops,
        public int $turns,
        public array|BlockedHitsData $monsterBlockedHits,
        public array|BlockedHitsData $playerBlockedHits,
        public array $logs,
        public FightResults|string $result,
    ) {
        $this->drops = DropData::collection($drops);
        $this->monsterBlockedHits = BlockedHitsData::from($monsterBlockedHits);
        $this->playerBlockedHits = BlockedHitsData::from($playerBlockedHits);
        $this->result = FightResults::fromValue($result);
    }
}
