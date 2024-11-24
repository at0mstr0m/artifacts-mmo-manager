<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\Schemas\SimpleItemData;
use Illuminate\Foundation\Bus\PendingDispatch;

class FightMonsterDrop extends FightMonster
{
    public function __construct(
        protected int $characterId,
        protected int $monsterId,
        protected SimpleItemData $itemData,
        protected int $tries = 0,
    ) {
        parent::__construct($characterId, $monsterId, $tries);
        $this->constructorArguments = compact(
            'characterId',
            'monsterId',
            'itemData',
            'tries'
        );
    }

    protected function handleWin(): PendingDispatch
    {
        return $this->character->hasInInventory($this->itemData)
            ? $this->dispatchNextJob()
            : $this->selfDispatch();
    }
}
