<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventData extends Data
{
    public Carbon $startedAt;

    public function __construct(
        public string $name,
        public string $code,
        public string $previousSkin,
        public int $duration,
        public Carbon|string $expiration,
        public array|MapData $map,
        string $createdAt,
    ) {
        $this->expiration = Carbon::parse($expiration);
        $this->startedAt = Carbon::parse($createdAt);
        $this->map = MapData::from($map);

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Event::firstWhere([
            'code' => $this->code,
            'started_at' => $this->startedAt,
        ]) ?? $this->map->getModel()->events()->create([
            'name' => $this->name,
            'code' => $this->code,
            'previous_skin' => $this->previousSkin,
            'duration' => $this->duration,
            'expiration' => $this->expiration,
            'started_at' => $this->startedAt,
        ]);
    }
}
