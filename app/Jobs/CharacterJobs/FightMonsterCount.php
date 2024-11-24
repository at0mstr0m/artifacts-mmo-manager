<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use Illuminate\Foundation\Bus\PendingDispatch;

class FightMonsterCount extends FightMonster
{
    public function __construct(
        protected int $characterId,
        protected int $monsterId,
        protected int $count = 1,
        protected int $tries = 0,
    ) {
        parent::__construct($characterId, $monsterId, $tries);
        $this->constructorArguments = compact(
            'characterId',
            'monsterId',
            'count',
            'tries'
        );
    }

    protected function handleWin(): PendingDispatch
    {
        return $this->count > 1
            ? $this->selfDispatch(['count' => $this->count - 1])
            : $this->dispatchNextJob();
    }
}
