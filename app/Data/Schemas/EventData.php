<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventData extends Data
{
    public function __construct(
        public string $name,
        public string $previousSkin,
        public int $duration,
        public Carbon|string $expiration,
        public Carbon|string $startedAt,
        public array|MapData $map,
    ) {
        $this->expiration = Carbon::parse($expiration);
        $this->startedAt = Carbon::parse($startedAt);
        $this->map = MapData::from($map);
        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Event::firstWhere([
            'name' => $this->name,
            'started_at' => $this->startedAt,
        ]) ?? $this->map->getModel()->map()->create([
            'name' => $this->name,
            'previous_skin' => $this->previousSkin,
            'duration' => $this->duration,
            'expiration' => $this->expiration,
            'started_at' => $this->startedAt,
        ]);
    }
}
