<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Casts\CarbonCast;
use App\Data\Schemas\AnnouncementData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class GetStatusData extends Data
{
    /**
     * @param  Collection<AnnouncementData>  $announcements
     */
    public function __construct(
        public string $status,
        public string $version,
        #[MapInputName('max_level')]
        public int $maxLevel,
        #[MapInputName('characters_online')]
        public int $charactersOnline,
        #[MapInputName('server_time')]
        #[WithCast(CarbonCast::class)]
        public Carbon $serverTime,
        public Collection $announcements,
        #[MapInputName('last_wipe')]
        #[WithCast(CarbonCast::class)]
        public Carbon $lastWipe,
        #[MapInputName('next_wipe')]
        public string $nextWipe,
    ) {}
}
