<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\AnnouncementData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GetStatusData extends Data
{
    /**
     * @param Collection<AnnouncementData> $announcements
     */
    public function __construct(
        public string $status,
        public string $version,
        public int $maxLevel,
        public int $charactersOnline,
        public Carbon|string $serverTime,
        public Collection $announcements,
        public Carbon|string $lastWipe,
        public null|Carbon|string $nextWipe,
    ) {
        $this->serverTime = Carbon::parse($serverTime);
        $this->announcements = AnnouncementData::collection($announcements);
        $this->lastWipe = Carbon::parse($lastWipe);
        $this->nextWipe = Carbon::parse($nextWipe);
    }
}
