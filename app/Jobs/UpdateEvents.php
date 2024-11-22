<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\Schemas\EventData;
use App\Models\Event;
use App\Services\ArtifactsService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateEvents implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function handle(): void
    {
        $changedIds = app(ArtifactsService::class)
            ->getAllEvents(all: true)
            ->map(fn (EventData $event): int => $event->getModel()->id);

        Event::whereNotIn('id', $changedIds)->delete();
    }
}
