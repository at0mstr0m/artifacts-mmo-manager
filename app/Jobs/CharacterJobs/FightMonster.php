<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Enums\FightResults;
use App\Jobs\CharacterJob;
use App\Models\Monster;
use Illuminate\Foundation\Bus\PendingDispatch;

abstract class FightMonster extends CharacterJob
{
    protected const int MAX_TRIES = 3;

    protected const int MAX_RESTS = 3;

    protected Monster $monster;

    public function __construct(
        protected int $characterId,
        protected int $monsterId,
        protected int $tries = 0,
        protected int $rests = 0,
    ) {}

    abstract protected function handleWin(): PendingDispatch;

    protected function handleCharacter(): void
    {
        $this->handleFullInventory();

        $this->handleMaxTriesOrRestsExceeded();

        $this->monster = Monster::find($this->monsterId);

        $this->ensureCharacterIsHealthy();

        $this->goToMonsterLocation();

        $this->fightMonster();
    }

    private function handleFullInventory(): void
    {
        if (! $this->character->inventoryIsFull()) {
            return;
        }
        $this->log('Inventory is full');
        $this->dispatchNextJob();

        $this->end();
    }

    private function handleMaxTriesOrRestsExceeded(): void
    {
        if (
            $this->tries < static::MAX_TRIES
            && $this->rests < static::MAX_RESTS
        ) {
            return;
        }

        $this->log('Max tries exceeded');

        $this->dispatchWithComeback(
            new ChooseBetterWeapon($this->characterId, $this->monsterId)
        );

        $this->end();
    }

    private function ensureCharacterIsHealthy(): void
    {
        if ($this->character->is_healthy) {
            $this->rests = 0;

            return;
        }

        $this->log('Not healthy enough to fight');
        $delay = $this->character->rest()->cooldown->expiresAt;
        ++$this->rests;
        $this->log("Resting for {$this->rests} time(s)");
        $this->selfDispatch()->delay($delay);

        $this->end();
    }

    private function goToMonsterLocation(): void
    {
        $currentLocation = $this->character->location;

        if (
            $currentLocation->content_type === 'monster'
            || $currentLocation->content_code === $this->monster->code
        ) {
            $this->log('Is at monster location');

            return;
        }

        $this->log('Not at monster location');
        $monsterLocation = $this->monster->locations()->inRandomOrder()->first();

        if (! $monsterLocation) {
            $this->fail("found no location for monster {$this->monster->name}");
        }

        $delay = $this
            ->character
            ->moveTo($monsterLocation)
            ->cooldown
            ->expiresAt;

        $this->selfDispatch()->delay($delay);

        $this->end();
    }

    private function fightMonster(): void
    {
        $this->log("fighting monster {$this->monster->name}");
        $data = $this->character->fight();
        $delay = $data->cooldown->expiresAt;
        $result = $data->fight->result;

        $this->log("fight result: {$result->value}");

        $hasWon = $result === FightResults::WIN;
        $this->tries = $hasWon ? 0 : $this->tries + 1;

        if ($hasWon) {
            $this->handleWin($delay)->delay($delay);

            return;
        }

        $this->selfDispatch(['tries' => $this->tries])->delay($delay);
    }
}
