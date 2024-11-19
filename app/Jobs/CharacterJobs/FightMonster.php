<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Enums\FightResults;
use App\Jobs\CharacterJob;
use App\Models\Monster;

class FightMonster extends CharacterJob
{
    protected Monster $monster;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $characterId,
        protected int $monsterId,
        protected int $tries = 0,
    ) {
        parent::__construct($characterId);
        $this->constructorArguments = compact('characterId', 'monsterId', 'tries');
        if ($tries > 3) {
            $this->fail(new \Exception(
                'Character '
                . $characterId
                . ' tried to fight monster '
                . $monsterId
                . ' too many times'
            ));
        }
    }

    /**
     * Execute the job.
     */
    public function handleCharacter(): void
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

        if ($this->character->isAt($monsterLocation)) {
            $this->log("fighting monster {$this->monster->name}");
            $data = $this->character->fight();
            $delay = $data->cooldown->expiresAt;
            $result = $data->fight->result;
            $this->log("fight result: {$result->value}");
            $this->tries = $result === FightResults::WIN ? 0 : $this->tries + 1;
        } else {
            $this->log("moving to monster {$this->monster->name}");
            $delay = $this
                ->character
                ->move($monsterLocation)
                ->cooldown
                ->expiresAt;
        }

        $this->log("delaying for {$delay->diffInSeconds(now())} Seconds");
        $this->selfDispatch(['tries' => $this->tries])->delay($delay);
    }
}
