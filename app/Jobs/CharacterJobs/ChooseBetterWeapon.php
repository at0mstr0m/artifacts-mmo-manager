<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Actions\UpdateBankDepositsAction;
use App\Data\NextJobData;
use App\Enums\Skills;
use App\Jobs\CharacterJob;
use App\Models\InventoryItem;
use App\Models\Item;
use App\Models\Monster;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ChooseBetterWeapon extends CharacterJob
{
    private Monster $monster;

    public function __construct(
        protected int $characterId,
        protected int $monsterId,
    ) {
        $this->constructorArguments = compact('characterId', 'monsterId');
    }

    protected function handleCharacter(): void
    {
        $this->ensureHasWeaponEquiped();

        $this->checkHasBetterWeaponInInventoryOrBank();

        $this->checkCouldCraftBetterWeapon();
    }

    private function ensureHasWeaponEquiped(): void
    {
        if ($this->character->weapon_slot) {
            $this->log('Character has a weapon equiped.');

            return;
        }

        $this->log('Character does not have a weapon equiped.');

        /** @var InventoryItem */
        $weapon = $this
            ->character
            ->inventoryItems()
            ->firstWhere('type', 'weapon');

        if ($weapon) {
            $this->log("Equipping {$weapon->code} from inventory.");
            $this->character->equip($weapon);

            return;
        }

        UpdateBankDepositsAction::run();

        $weapon = Item::query()
            ->onlyDeposited()
            ->firstWhere('type', 'weapon');

        if ($weapon) {
            $this->log("Must withdraw weapon {$weapon->code} from bank.");
            $this->dispatchWithComeback(
                new WithdrawFromBank(
                    $this->characterId,
                    $weapon->code,
                    1,
                )
            );

            $this->end();
        }

        $this->fail('No weapon found in inventory or bank.');
    }

    private function checkHasBetterWeaponInInventoryOrBank(): void
    {
        $this->monster = Monster::find($this->monsterId);
        $this->log('Checking for better weapon against monster: ' . $this->monster->name);
        $currentWeapon = Item::findByCode($this->character->weapon_slot);

        if ($this->monster->level <= $currentWeapon->level) {
            $this->log('Current weapon should be strong enough.');
        }

        $this->log('Current weapon is not strong enough.');

        /** @var InventoryItem */
        $weapon = $this
            ->character
            ->inventoryItems()
            ->whereRelation('item', fn (Builder $query) => $query
                ->where('level', '>', $currentWeapon->level)
                ->where('type', 'weapon'))
            ->get()
            ->sortByDesc(fn (InventoryItem $item) => $item->item->level)
            ->first();

        if ($weapon) {
            $this->log("Equipping {$weapon->code} from inventory.");
            $this->character->equip($weapon);

            return;
        }

        UpdateBankDepositsAction::run();

        $weapon = Item::query()
            ->onlyDeposited()
            ->where('level', '>', $currentWeapon->level)
            ->where('type', 'weapon')
            ->orderByDesc('level')
            ->first();

        if ($weapon) {
            $this->log("Must withdraw weapon {$weapon->code} from bank.");
            $this->unshiftNextJob(new NextJobData(
                WithdrawFromBank::class,
                [
                    'characterId' => $this->character->id,
                    'itemCode' => $weapon->code,
                    'quantity' => 1,
                ]
            ));

            $this->end();
        }
    }

    private function checkCouldCraftBetterWeapon(): void
    {
        $this->log('Checking for better weapon to craft.');

        $currentWeapon = Item::findByCode($this->character->weapon_slot);

        if ($this->monster->level <= $currentWeapon->level) {
            $this->log("Current weapon {$currentWeapon->name} should be strong enough.");

            return;
        }

        $currenWeaponCraftingLevel = $this->character->weaponcrafting_level;
        $this->log("Current weapon crafting skill: {$currenWeaponCraftingLevel}");

        $newWeapon = Item::query()
            ->where('type', 'weapon')
            ->where('level', '>', $currentWeapon->level)
            ->where('level', '<=', $currenWeaponCraftingLevel)
            ->orderByDesc('level')
            ->first();

        if ($newWeapon) {
            $this->log("Found better weapon {$newWeapon->name} to craft.");
            $this->dispatchWithComeback(
                new CollectRawMaterialsToCraft(
                    $this->character->id,
                    $newWeapon->id,
                    1
                )
            );

            $this->end();
        }

        if ($currenWeaponCraftingLevel < Item::maxLevel()) {
            $this->log("Current weapon crafting skill {$currenWeaponCraftingLevel} is not maxed.");

            $this->dispatchWithComeback(
                new CollectSkillXp(
                    $this->character->id,
                    Skills::WEAPON_CRAFTING,
                    $currenWeaponCraftingLevel + 1
                )
            );

            $this->end();
        }

        $this->log('No better weapon found to craft.');
    }
}
