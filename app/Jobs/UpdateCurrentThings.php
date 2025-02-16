<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\Schemas\EventData;
use App\Models\Account;
use App\Models\Event;
use App\Models\SellOrder;
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
        $this->updateGrandExchange();
    }

    private function updateEvents(): void
    {
        $changedIds = $this
            ->api
            ->getAllEvents(all: true)
            ->map(fn (EventData $event): int => $event->getModel()->id);

        Event::query()
            ->whereNotIn('id', $changedIds)
            ->delete();
    }

    private function updateAchievements(): void
    {
        $this->api->getAccountAchievements(
            Account::query()->first()->username,
            all: true
        );
    }

    private function updateGrandExchange(): void
    {
        $history = $this->api->getGeSellHistory(all: true);
        $current = $this->api->getGeSellOrders(all: true);

        $identifiers = $history->concat($current)
            ->pluck('identifier')
            ->unique();

        SellOrder::query()
            ->whereNotIn('identifier', $identifiers)
            ->delete();
    }
}
