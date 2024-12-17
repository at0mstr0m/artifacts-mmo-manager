<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\Schemas\EventData;
use App\Models\Account;
use App\Models\Event;
use App\Services\ArtifactsService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateCurrentThings implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    private ArtifactsService $api;

    public function __construct() {}

    public function handle(): void
    {
        $this->api = app(ArtifactsService::class);
        $this->updateEvents();
        $this->updateAchievements();
    }

    private function updateEvents(): void
    {
        $changedIds = $this
            ->api
            ->getAllEvents(all: true)
            ->map(fn (EventData $event): int => $event->getModel()->id);

        Event::whereNotIn('id', $changedIds)->delete();
    }

    private function updateAchievements(): void
    {
        $this->api->getAccountAchievements(
            Account::first()->username,
            all: true
        );
    }
}
