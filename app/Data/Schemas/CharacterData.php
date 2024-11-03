<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Character;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CharacterData extends Data
{
    private Character $model;

    /**
     * @param Collection<InventorySlotData> $inventory
     */
    public function __construct(
        public string $name,
        public string $skin,
        public int $level,
        public int $xp,
        public int $maxXp,
        public int $achievementsPoints,
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
        public int $hp,
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
        public string $consumable1Slot,
        public int $consumable1SlotQuantity,
        public string $consumable2Slot,
        public int $consumable2SlotQuantity,
        public string $task,
        public string $taskType,
        public int $taskProgress,
        public int $taskTotal,
        public int $inventoryMaxItems,
        public array|Collection $inventory
    ) {
        $this->cooldownExpiration = Carbon::parse($cooldownExpiration);
        $this->inventory = InventorySlotData::collection($inventory);
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
        ], [
            'skin' => $this->skin,
            'level' => $this->level,
            'xp' => $this->xp,
            'max_xp' => $this->maxXp,
            'achievements_points' => $this->achievementsPoints,
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
            'hp' => $this->hp,
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
            'consumable1_slot' => $this->consumable1Slot,
            'consumable1_slot_quantity' => $this->consumable1SlotQuantity,
            'consumable2_slot' => $this->consumable2Slot,
            'consumable2_slot_quantity' => $this->consumable2SlotQuantity,
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
