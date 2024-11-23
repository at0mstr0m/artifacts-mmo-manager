<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Enums\FightResults;
use App\Jobs\CharacterJob;
use App\Models\Monster;
use Illuminate\Foundation\Bus\PendingDispatch;

abstract class FightMonster extends CharacterJob
{
    protected Monster $monster;

    public function __construct(
        int $characterId,
        protected int $monsterId,
        protected int $tries = 0,
    ) {
        if ($tries > 3) {
            $this->fail(new \Exception(
                'Character '
                . $characterId
                . ' tried to fight monster '
                . $monsterId
                . ' too many times'
            ));
        }
        parent::__construct($characterId);
    }

    abstract protected function handleWin(): PendingDispatch;

    protected function handleCharacter(): void
    {
        $this->monster = Monster::find($this->monsterId);

        $monsterLocation = $this->monster->locations()->inRandomOrder()->first();

        if (! $monsterLocation) {
            throw new \Exception(
                "found no location for monster {$this->monster->name}",
                1
            );
        }

        if (! $this->character->is_healthy) {
            $this->log('Not healthy enough to fight');
            $delay = $this->character->rest()->cooldown->expiresAt;
            $this->selfDispatch()->delay($delay);

            $this->log('Resting');

            return;
        }

        if (! $this->character->isAt($monsterLocation)) {
            $this->log("moving to monster {$this->monster->name}");
            $delay = $this
                ->character
                ->move($monsterLocation)
                ->cooldown
                ->expiresAt;

            $this->selfDispatch()->delay($delay);

            return;
        }

        $this->log("fighting monster {$this->monster->name}");
        $data = $this->character->fight();
        $delay = $data->cooldown->expiresAt;
        $result = $data->fight->result;
        $this->log("fight result: {$result->value}");
        $hasWon = $result === FightResults::WIN;
        $this->tries = $result === FightResults::WIN ? 0 : $this->tries + 1;

        if ($hasWon) {
            $this->handleWin($delay)->delay($delay);

            return;
        }

        $this->selfDispatch(['tries' => $this->tries])->delay($delay);
    }
}
