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
                'monster_blocked_hits_fire' => $this->fight->monsterBlockedHits->fire,
                'monster_blocked_hits_earth' => $this->fight->monsterBlockedHits->earth,
                'monster_blocked_hits_water' => $this->fight->monsterBlockedHits->water,
                'monster_blocked_hits_air' => $this->fight->monsterBlockedHits->air,
                'monster_blocked_hits_total' => $this->fight->monsterBlockedHits->total,
                'player_blocked_hits_fire' => $this->fight->playerBlockedHits->fire,
                'player_blocked_hits_earth' => $this->fight->playerBlockedHits->earth,
                'player_blocked_hits_water' => $this->fight->playerBlockedHits->water,
                'player_blocked_hits_air' => $this->fight->playerBlockedHits->air,
                'player_blocked_hits_total' => $this->fight->playerBlockedHits->total,
                'result' => $this->fight->result->value,
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
