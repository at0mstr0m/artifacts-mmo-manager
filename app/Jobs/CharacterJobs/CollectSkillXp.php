<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Enums\Skills;
use App\Jobs\CharacterJob;
use App\Models\Craft;
use App\Models\Resource;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CollectSkillXp extends CharacterJob
{
    public function __construct(
        protected int $characterId,
        protected Skills $skill,
        protected int $desiredLevel,
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'skill',
            'desiredLevel'
        );
    }

    protected function handleCharacter(): void
    {
        $this->checkHasReachedLevel();

        $this->emptyHalfFullInventory();

        $this->triggerItemFarming();
    }

    private function checkHasReachedLevel(): void
    {
        if ($this->character->hasSkillLevel($this->skill, $this->desiredLevel)) {
            $this->log(
                'Has reached desired Level '
                . $this->desiredLevel
                . ' in '
                . $this->skill->value
            );

            $this->end();
        }

        $this->log(
            'Has not reached desired Level '
            . $this->desiredLevel
            . ' in '
            . $this->skill->value
        );
    }

    private function emptyHalfFullInventory(): void
    {
        $half = $this->character->inventory_max_items / 2;
        if ($this->character->inventory_count > $half) {
            $this->log('Inventory is half full');
            $this->dispatchWithComeback(new EmptyInventory($this->character->id));

            $this->end();
        }
    }

    private function triggerItemFarming(): void
    {
        $this->log('Triggering item farming');

        $currentLevel = $this->character->getSkillLevel($this->skill);
        $skillLevels = $this->character->getSkillLevels();
        $item = Craft::query()
            ->where('skill', $this->skill)
            ->where('level', '<=', $currentLevel)
            ->whereRelation(
                'requiredItems',
                fn (Builder $query) => $query->whereRelation(
                    'drops',
                    fn (Builder $query) => $query->whereMorphRelation(
                        'source',
                        Resource::class,
                        fn (Builder $query) => $skillLevels->each(
                            function (int $level, string $skill) use (&$query) {
                                $query->where(
                                    fn (Builder $query) => $query
                                        ->where('skill', $skill)
                                        ->where('level', '<=', $level),
                                    boolean: 'or'
                                );
                            }
                        )
                    )
                )->orWhereRelation(
                    'craft',
                    fn (Builder $query) => $skillLevels->each(
                        function (int $level, string $skill) use (&$query) {
                            $query->where(
                                fn (Builder $query) => $query
                                    ->where('skill', $skill)
                                    ->where('level', '<=', $level),
                                boolean: 'or'
                            );
                        }
                    )
                )
            )
            ->whereRelation('item', 'code', '<>', 'wooden_staff')
            ->orderByDesc('level')
            ->first()
            ?->item;

        if ($item) {
            $this->log('Item to farm: ' . $item->name);
            $this->dispatchWithComeback(
                new CollectRawMaterialsToCraft(
                    $this->characterId,
                    $item->id,
                    $this->character->countInInventory($item) + 1
                )
            );

            $this->end();
        }

        $this->log('No item to farm, must farm resource');

        $resource = Resource::query()
            ->where('skill', $this->skill)
            ->where('level', '<=', $currentLevel)
            ->orderByDesc('level')
            ->first();

        if ($resource) {
            $this->log('Resource to farm: ' . $resource->name);
            $this->dispatchWithComeback(
                new FarmResource($this->characterId, $resource->id)
            );

            $this->end();
        }

        $this->log(
            'Found no Item or Resource to farm xp for '
            . $this->skill->value
        );
    }
}
