<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\FightData;
use Illuminate\Support\Arr;

class ActionFightData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param FightData $fight
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|FightData $fight,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->fight = FightData::from($fight);
        $this->character = CharacterData::from($character);

        $this->character
            ->getModel()
            ->fights()
            ->create([
                'xp' => $this->fight->xp,
                'gold' => $this->fight->gold,
                'turns' => $this->fight->turns,
                'result' => $this->fight->result->value,
                'drops' => $this->fight->drops->toArray()
            ])
            ->logs()
            ->createMany(
                Arr::map(
                    $this->fight->logs,
                    fn (string $text) => ['text' => $text]
                )
            );
    }
}
