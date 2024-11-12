<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SimpleItemData;
use Illuminate\Support\Collection;

class ActionCompleteTaskData extends Data
{
    public int $rewardedGold;

    /**
     * @var Collection<SimpleItemData>
     */
    public Collection $rewardedItems;

    /**
     * @param CooldownData $cooldown
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array $rewards,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->rewardedGold = $rewards['gold'];
        $this->rewardedItems = SimpleItemData::collection($rewards['items']);
        $this->character = CharacterData::from($character);
    }
}
