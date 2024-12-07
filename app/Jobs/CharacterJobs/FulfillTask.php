<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\NextJobData;
use App\Data\Schemas\SimpleItemData;
use App\Enums\TaskTypes;
use App\Jobs\CharacterJob;
use App\Models\Character;
use App\Models\Item;
use App\Models\Map;
use App\Models\Monster;

class FulfillTask extends CharacterJob
{
    public function __construct(
        protected int $characterId,
        protected ?TaskTypes $type = null,
    ) {
        $this->type ??= Character::find($this->characterId)->task_type
            ?? TaskTypes::random();
        $this->constructorArguments = compact('characterId', 'type');
    }

    protected function handleCharacter(): void
    {
        $this->ensureHasTask();

        $this->handleFullInventory();

        $this->handleFulfillment();

        $this->claimRewards();
    }

    private function ensureHasTask(): void
    {
        if ($this->character->task_type) {
            return;
        }

        $this->log('No task to fulfill, go to task master and get one.');

        $taskMasterLocation = $this->getTaskMasterLocation();

        if ($this->character->isAt($taskMasterLocation)) {
            $this->log('At task master, getting a new task.');
            $data = $this->character->acceptNewTask();
            $this->log("Got a new task: {$data->task->code}.");
            $this->character = $data->character->getModel();
        } else {
            $moveData = $this->character->moveTo($taskMasterLocation);
            $this->log('Moving to task master to get a new task.');
            $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

            $this->end();
        }
    }

    private function handleFullInventory(): void
    {
        if (! $this->character->inventoryIsFull()) {
            $unfulfilled = $this->character->task_total - $this->character->task_progress;
            if (! $unfulfilled) {
                $this->log('Task requirements are fulfilled.');

                return;
            }

            if ($this->character->countInInventory($this->character->task) === $unfulfilled) {
                $this->log('Inventory contains last task items to be able to fulfill the task.');
                $this->dispatchWithComeback(
                    new TradeTaskItems($this->character->id)
                );

                $this->end();
            }

            return;
        }

        switch ($this->type) {
            case TaskTypes::MONSTERS:
                $this->dispatchWithComeback(
                    new EmptyInventory($this->character->id)
                );
                break;
            case TaskTypes::ITEMS:
                $itemCode = $this->character->task;
                $item = Item::findByCode($itemCode);
                $job = (new EmptyInventory(
                    $this->character->id,
                    collect([
                        new SimpleItemData(
                            $itemCode,
                            $this->character->countInInventory($itemCode),
                        ),
                        ...($item->craft?->requiredItems?->map(
                            fn (Item $requiredItem): SimpleItemData => new SimpleItemData(
                                $requiredItem->code,
                                $requiredItem->pivot->quantity * ($this->character->task_total - $this->character->task_progress),
                            )
                        )->all() ?? []),
                    ])
                ))
                    ->setNextJobs($this->nextJobs)
                    ->unshiftNextJob($this->makeNextJobData())
                    ->unshiftNextJob(new NextJobData(
                        TradeTaskItems::class,
                        ['characterId' => $this->character->id]
                    ));
                dispatch($job);
                break;
        }

        $this->end();
    }

    private function handleFulfillment(): void
    {
        $this->log("Fulfilling task: {$this->character->task}.");

        $progress = match ($this->type) {
            TaskTypes::MONSTERS => (
                $this->character->task_total - $this->character->task_progress
            ) ,
            TaskTypes::ITEMS => (
                ($this->character->task_total - $this->character->task_progress)
                    - $this->character->countInInventory($this->character->task)
            ),
        };

        if ($progress <= 0) {
            $this->log('Task requirements fulfilled.');

            return;
        }

        $this->log('Task is not fulfilled yet, fulfilling it.');
        $this->log("Progress yet: {$progress}/{$this->character->task_total}.");

        $payload = [
            'characterId' => $this->character->id,
            'count' => $this->character->task_total,
        ];

        if ($this->type === TaskTypes::MONSTERS) {
            $monsterId = Monster::findByCode($this->character->task)->id;
            $this->dispatchWithComeback(
                new FightMonsterCount(
                    ...[...$payload, 'monsterId' => $monsterId]
                )
            );
        } else {    // $this->type === TaskTypes::ITEMS
            $item = Item::findByCode($this->character->task);
            $payload['itemId'] = $item->id;
            $job = $item->craft
                ? new CollectRawMaterialsToCraft(...$payload)
                : new GatherItem(...$payload);
            $this->dispatchWithComeback($job);
        }

        $this->end();
    }

    private function claimRewards(): void
    {
        $this->log('Claiming task reward.');

        $taskMasterLocation = $this->getTaskMasterLocation();

        if ($this->character->isAt($taskMasterLocation)) {
            $this->log('At task master, claiming reward.');
            $data = $this->character->completeTask();
            $this->log("Claimed reward: {$data->rewardedGold} Gold.");
        } else {
            $moveData = $this->character->moveTo($taskMasterLocation);
            $this->log('Moving to task master to claim reward.');
            $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

            $this->end();
        }

        $this->log('Task fulfilled and reward claimed.');
    }

    private function getTaskMasterLocation(): Map
    {
        return Map::firstWhere([
            'content_type' => 'tasks_master',
            'content_code' => $this->type,
        ]);
    }
}
