<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventData extends Data
{
    public Carbon $startedAt;

    protected Event $event;

    public function __construct(
        public string $name,
        public string $code,
        public array|string $previousMap,
        public int $duration,
        public Carbon|string $expiration,
        public array|MapData $map,
        string $createdAt,
    ) {
        $this->expiration = Carbon::parse($expiration);
        $this->startedAt = Carbon::parse($createdAt);
        $this->map = MapData::from($map);
        $this->previousMap = $previousMap['skin'];  // the other attributes are irrelevant

        $this->createIfNotExists();
    }

    public function getModel(): Event
    {
        return $this->event;
    }

    private function createIfNotExists(): void
    {
        $this->event = Event::firstWhere([
            'code' => $this->code,
            'started_at' => $this->startedAt,
        ]) ?? $this->map->getModel()->events()->create([
            'name' => $this->name,
            'code' => $this->code,
            'previous_map' => $this->previousMap,
            'duration' => $this->duration,
            'expiration' => $this->expiration,
            'started_at' => $this->startedAt,
        ]);
    }
}
