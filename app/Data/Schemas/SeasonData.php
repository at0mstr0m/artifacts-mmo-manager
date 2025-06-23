<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SeasonData extends Data
{
    /**
     * @param Collection<SeasonBadgeData> $badges
     * @param Collection<SeasonSkinData> $skins
     */
    public function __construct(
        public string $name,
        public int $number,
        public Carbon|string $startDate,
        public array|Collection $badges,
        public array|Collection $skins,
    ) {
        $this->startDate = Carbon::parse($startDate);
        $this->badges = SeasonBadgeData::collection($badges);
        $this->skins = SeasonSkinData::collection($skins);
    }
}
