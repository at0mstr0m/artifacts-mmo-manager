<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Jobs\CharacterJob;
use App\Models\Map;

class TradeTaskItems extends CharacterJob
{
    protected function handleCharacter(): void
    {
        $this->ensureIsAtTaskMaster();

        $this->tradeInTaskItems();
    }

    private function ensureIsAtTaskMaster(): void
    {
        /** @var Map */
        $taskMasterLocation = Map::firstWhere([
            'content_type' => 'tasks_master',
            'content_code' => $this->character->task_type,
        ]);

        if ($this->character->isAt($taskMasterLocation)) {
            return;
        }

        $this->log('Moving to task master.');
        $moveData = $this->character->moveTo($taskMasterLocation);
        $this->selfDispatch()->delay($moveData->cooldown->expiresAt);
        $this->end();
    }

    private function tradeInTaskItems(): void
    {
        $this->log('Trading in task items.');
        $data = $this->character->tradeTaskItems();

        $this->dispatchNextJob()->delay($data->cooldown->expiresAt);
    }
}
