<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\TaskData;

class ActionAcceptNewTask extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param TaskData $task
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|TaskData $task,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->task = TaskData::from($task);
        $this->character = CharacterData::from($character);
    }
}
