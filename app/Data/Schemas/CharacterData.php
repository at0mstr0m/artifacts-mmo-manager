<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\CharacterSkins;
use App\Enums\TaskTypes;
use App\Models\Character;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CharacterData extends Data
{
    private Character $model;

    /**
     * @param Collection<InventorySlotData> $inventory
     * @param CharacterSkins $skin
     * @param TaskTypes $taskType
     */
    public function __construct(
        public string $name,
        public string $account,
        public CharacterSkins|string $skin,
        public int $level,
        public int $xp,
        public int $maxXp,
        public int $gold,
        public int $speed,
        public int $miningLevel,
        public int $miningXp,
        public int $miningMaxXp,
        public int $woodcuttingLevel,
        public int $woodcuttingXp,
        public int $woodcuttingMaxXp,
        public int $fishingLevel,
        public int $fishingXp,
        public int $fishingMaxXp,
        public int $weaponcraftingLevel,
        public int $weaponcraftingXp,
        public int $weaponcraftingMaxXp,
        public int $gearcraftingLevel,
        public int $gearcraftingXp,
        public int $gearcraftingMaxXp,
        public int $jewelrycraftingLevel,
        public int $jewelrycraftingXp,
        public int $jewelrycraftingMaxXp,
        public int $cookingLevel,
        public int $cookingXp,
        public int $cookingMaxXp,
        public int $alchemyLevel,
        public int $alchemyXp,
        public int $alchemyMaxXp,
        public int $hp,
        public int $maxHp,
        public int $haste,
        public int $criticalStrike,
        public int $stamina,
        public int $attackFire,
        public int $attackEarth,
        public int $attackWater,
        public int $attackAir,
        public int $dmgFire,
        public int $dmgEarth,
        public int $dmgWater,
        public int $dmgAir,
        public int $resFire,
        public int $resEarth,
        public int $resWater,
        public int $resAir,
        public int $x,
        public int $y,
        public int $cooldown,
        public Carbon|string $cooldownExpiration,
        public string $weaponSlot,
        public string $shieldSlot,
        public string $helmetSlot,
        public string $bodyArmorSlot,
        public string $legArmorSlot,
        public string $bootsSlot,
        public string $ring1Slot,
        public string $ring2Slot,
        public string $amuletSlot,
        public string $artifact1Slot,
        public string $artifact2Slot,
        public string $artifact3Slot,
        public string $utility1Slot,
        public int $utility1SlotQuantity,
        public string $utility2Slot,
        public int $utility2SlotQuantity,
        public string $task,
        public null|string|TaskTypes $taskType,
        public int $taskProgress,
        public int $taskTotal,
        public int $inventoryMaxItems,
        public array|Collection $inventory
    ) {
        $this->cooldownExpiration = Carbon::parse($cooldownExpiration);
        $this->inventory = InventorySlotData::collection($inventory);
        $this->skin = CharacterSkins::fromValue($skin);
        $this->taskType = $this->taskType ? TaskTypes::fromValue($taskType) : null;

        $this->createIfNotExists();
    }

    public function getModel(): Character
    {
        return $this->model;
    }

    private function createIfNotExists(): void
    {
        $this->model = Character::updateOrCreate([
            'name' => $this->name,
            'account' => $this->account,
        ], [
            'skin' => $this->skin,
            'level' => $this->level,
            'xp' => $this->xp,
            'max_xp' => $this->maxXp,
            'gold' => $this->gold,
            'speed' => $this->speed,
            'mining_level' => $this->miningLevel,
            'mining_xp' => $this->miningXp,
            'mining_max_xp' => $this->miningMaxXp,
            'woodcutting_level' => $this->woodcuttingLevel,
            'woodcutting_xp' => $this->woodcuttingXp,
            'woodcutting_max_xp' => $this->woodcuttingMaxXp,
            'fishing_level' => $this->fishingLevel,
            'fishing_xp' => $this->fishingXp,
            'fishing_max_xp' => $this->fishingMaxXp,
            'weaponcrafting_level' => $this->weaponcraftingLevel,
            'weaponcrafting_xp' => $this->weaponcraftingXp,
            'weaponcrafting_max_xp' => $this->weaponcraftingMaxXp,
            'gearcrafting_level' => $this->gearcraftingLevel,
            'gearcrafting_xp' => $this->gearcraftingXp,
            'gearcrafting_max_xp' => $this->gearcraftingMaxXp,
            'jewelrycrafting_level' => $this->jewelrycraftingLevel,
            'jewelrycrafting_xp' => $this->jewelrycraftingXp,
            'jewelrycrafting_max_xp' => $this->jewelrycraftingMaxXp,
            'cooking_level' => $this->cookingLevel,
            'cooking_xp' => $this->cookingXp,
            'cooking_max_xp' => $this->cookingMaxXp,
            'alchemy_level' => $this->alchemyLevel,
            'alchemy_xp' => $this->alchemyXp,
            'alchemy_max_xp' => $this->alchemyMaxXp,
            'hp' => $this->hp,
            'max_hp' => $this->maxHp,
            'haste' => $this->haste,
            'critical_strike' => $this->criticalStrike,
            'stamina' => $this->stamina,
            'attack_fire' => $this->attackFire,
            'attack_earth' => $this->attackEarth,
            'attack_water' => $this->attackWater,
            'attack_air' => $this->attackAir,
            'dmg_fire' => $this->dmgFire,
            'dmg_earth' => $this->dmgEarth,
            'dmg_water' => $this->dmgWater,
            'dmg_air' => $this->dmgAir,
            'res_fire' => $this->resFire,
            'res_earth' => $this->resEarth,
            'res_water' => $this->resWater,
            'res_air' => $this->resAir,
            'x' => $this->x,
            'y' => $this->y,
            'cooldown' => $this->cooldown,
            'cooldown_expiration' => $this->cooldownExpiration,
            'weapon_slot' => $this->weaponSlot,
            'shield_slot' => $this->shieldSlot,
            'helmet_slot' => $this->helmetSlot,
            'body_armor_slot' => $this->bodyArmorSlot,
            'leg_armor_slot' => $this->legArmorSlot,
            'boots_slot' => $this->bootsSlot,
            'ring1_slot' => $this->ring1Slot,
            'ring2_slot' => $this->ring2Slot,
            'amulet_slot' => $this->amuletSlot,
            'artifact1_slot' => $this->artifact1Slot,
            'artifact2_slot' => $this->artifact2Slot,
            'artifact3_slot' => $this->artifact3Slot,
            'utility1_slot' => $this->utility1Slot,
            'utility1_slot_quantity' => $this->utility1SlotQuantity,
            'utility2_slot' => $this->utility2Slot,
            'utility2_slot_quantity' => $this->utility2SlotQuantity,
            'task' => $this->task,
            'task_type' => $this->taskType,
            'task_progress' => $this->taskProgress,
            'task_total' => $this->taskTotal,
            'inventory_max_items' => $this->inventoryMaxItems,
        ]);

        $this->model->inventoryItems()->delete();

        $this->model->inventoryItems()->createMany(
            $this->inventory->map(fn (InventorySlotData $slot): array => [
                'slot' => $slot->slot,
                'code' => $slot->code,
                'quantity' => $slot->quantity,
            ])
        );
    }
}
