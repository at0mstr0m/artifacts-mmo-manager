<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\AnnouncementData;
use App\Data\Schemas\RateLimitData;
use App\Data\Schemas\SeasonData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GetServerDetailsData extends Data
{
    /**
     * @param Collection<SeasonData> $season
     * @param Collection<AnnouncementData> $announcements
     * @param Collection<RateLimitData> $rateLimits
     */
    public function __construct(
        public string $version,
        public Carbon|string $serverTime,
        public int $maxLevel,
        public int $maxSkillLevel,
        public int $charactersOnline,
        public array|SeasonData $season,
        public array|Collection $announcements,
        public array|Collection $rateLimits,
    ) {
        $this->serverTime = Carbon::parse($serverTime);
        $this->season = SeasonData::from($season);
        $this->announcements = AnnouncementData::collection($announcements);
        $this->rateLimits = RateLimitData::collection($rateLimits);
    }
}
