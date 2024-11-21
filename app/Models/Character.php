<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\Responses\ActionFightData;
use App\Data\Responses\ActionGatheringData;
use App\Data\Responses\ActionMoveData;
use App\Data\Responses\ActionRestData;
use App\Data\Schemas\SimpleItemData;
use App\Enums\CharacterSkins;
use App\Services\ArtifactsService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string $account
 * @property CharacterSkins $skin
 * @property int $level
 * @property int $xp
 * @property int $max_xp
 * @property int $gold
 * @property int $speed
 * @property int $mining_level
 * @property int $mining_xp
 * @property int $mining_max_xp
 * @property int $woodcutting_level
 * @property int $woodcutting_xp
 * @property int $woodcutting_max_xp
 * @property int $fishing_level
 * @property int $fishing_xp
 * @property int $fishing_max_xp
 * @property int $weaponcrafting_level
 * @property int $weaponcrafting_xp
 * @property int $weaponcrafting_max_xp
 * @property int $gearcrafting_level
 * @property int $gearcrafting_xp
 * @property int $gearcrafting_max_xp
 * @property int $jewelrycrafting_level
 * @property int $jewelrycrafting_xp
 * @property int $jewelrycrafting_max_xp
 * @property int $cooking_level
 * @property int $cooking_xp
 * @property int $cooking_max_xp
 * @property int $alchemy_level
 * @property int $alchemy_xp
 * @property int $alchemy_max_xp
 * @property int $hp
 * @property int $max_hp
 * @property int $haste
 * @property int $critical_strike
 * @property int $stamina
 * @property int $attack_fire
 * @property int $attack_earth
 * @property int $attack_water
 * @property int $attack_air
 * @property int $dmg_fire
 * @property int $dmg_earth
 * @property int $dmg_water
 * @property int $dmg_air
 * @property int $res_fire
 * @property int $res_earth
 * @property int $res_water
 * @property int $res_air
 * @property int $x
 * @property int $y
 * @property int $cooldown
 * @property Carbon $cooldown_expiration
 * @property string $weapon_slot
 * @property string $shield_slot
 * @property string $helmet_slot
 * @property string $body_armor_slot
 * @property string $leg_armor_slot
 * @property string $boots_slot
 * @property string $ring1_slot
 * @property string $ring2_slot
 * @property string $amulet_slot
 * @property string $artifact1_slot
 * @property string $artifact2_slot
 * @property string $artifact3_slot
 * @property string $utility1_slot
 * @property int $utility1_slot_quantity
 * @property string $utility2_slot
 * @property int $utility2_slot_quantity
 * @property string $task
 * @property string $task_type
 * @property int $task_progress
 * @property int $task_total
 * @property int $inventory_max_items
 * @property int|null $occupaion_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fight> $fights
 * @property-read int|null $fights_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @property-read bool $is_healthy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $logs
 * @property-read int|null $logs_count
 * @property-read Occupaion|null $occupation
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Character newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Character newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Character query()
 *
 * @mixin \Eloquent
 */
class Character extends Model
{
    protected $fillable = [
        'name',
        'account',
        'skin',
        'level',
        'xp',
        'max_xp',
        'gold',
        'speed',
        'mining_level',
        'mining_xp',
        'mining_max_xp',
        'woodcutting_level',
        'woodcutting_xp',
        'woodcutting_max_xp',
        'fishing_level',
        'fishing_xp',
        'fishing_max_xp',
        'weaponcrafting_level',
        'weaponcrafting_xp',
        'weaponcrafting_max_xp',
        'gearcrafting_level',
        'gearcrafting_xp',
        'gearcrafting_max_xp',
        'jewelrycrafting_level',
        'jewelrycrafting_xp',
        'jewelrycrafting_max_xp',
        'cooking_level',
        'cooking_xp',
        'cooking_max_xp',
        'alchemy_level',
        'alchemy_xp',
        'alchemy_max_xp',
        'hp',
        'max_hp',
        'haste',
        'critical_strike',
        'stamina',
        'attack_fire',
        'attack_earth',
        'attack_water',
        'attack_air',
        'dmg_fire',
        'dmg_earth',
        'dmg_water',
        'dmg_air',
        'res_fire',
        'res_earth',
        'res_water',
        'res_air',
        'x',
        'y',
        'cooldown',
        'cooldown_expiration',
        'weapon_slot',
        'shield_slot',
        'helmet_slot',
        'body_armor_slot',
        'leg_armor_slot',
        'boots_slot',
        'ring1_slot',
        'ring2_slot',
        'amulet_slot',
        'artifact1_slot',
        'artifact2_slot',
        'artifact3_slot',
        'utility1_slot',
        'utility1_slot_quantity',
        'utility2_slot',
        'utility2_slot_quantity',
        'task',
        'task_type',
        'task_progress',
        'task_total',
        'inventory_max_items',
    ];

    protected $casts = [
        'name' => 'string',
        'account' => 'string',
        'skin' => CharacterSkins::class,
        'level' => 'integer',
        'xp' => 'integer',
        'max_xp' => 'integer',
        'gold' => 'integer',
        'speed' => 'integer',
        'mining_level' => 'integer',
        'mining_xp' => 'integer',
        'mining_max_xp' => 'integer',
        'woodcutting_level' => 'integer',
        'woodcutting_xp' => 'integer',
        'woodcutting_max_xp' => 'integer',
        'fishing_level' => 'integer',
        'fishing_xp' => 'integer',
        'fishing_max_xp' => 'integer',
        'weaponcrafting_level' => 'integer',
        'weaponcrafting_xp' => 'integer',
        'weaponcrafting_max_xp' => 'integer',
        'gearcrafting_level' => 'integer',
        'gearcrafting_xp' => 'integer',
        'gearcrafting_max_xp' => 'integer',
        'jewelrycrafting_level' => 'integer',
        'jewelrycrafting_xp' => 'integer',
        'jewelrycrafting_max_xp' => 'integer',
        'cooking_level' => 'integer',
        'cooking_xp' => 'integer',
        'cooking_max_xp' => 'integer',
        'alchemy_level' => 'integer',
        'alchemy_xp' => 'integer',
        'alchemy_max_xp' => 'integer',
        'hp' => 'integer',
        'max_hp' => 'integer',
        'haste' => 'integer',
        'critical_strike' => 'integer',
        'stamina' => 'integer',
        'attack_fire' => 'integer',
        'attack_earth' => 'integer',
        'attack_water' => 'integer',
        'attack_air' => 'integer',
        'dmg_fire' => 'integer',
        'dmg_earth' => 'integer',
        'dmg_water' => 'integer',
        'dmg_air' => 'integer',
        'res_fire' => 'integer',
        'res_earth' => 'integer',
        'res_water' => 'integer',
        'res_air' => 'integer',
        'x' => 'integer',
        'y' => 'integer',
        'cooldown' => 'integer',
        'cooldown_expiration' => 'datetime',
        'weapon_slot' => 'string',
        'shield_slot' => 'string',
        'helmet_slot' => 'string',
        'body_armor_slot' => 'string',
        'leg_armor_slot' => 'string',
        'boots_slot' => 'string',
        'ring1_slot' => 'string',
        'ring2_slot' => 'string',
        'amulet_slot' => 'string',
        'artifact1_slot' => 'string',
        'artifact2_slot' => 'string',
        'artifact3_slot' => 'string',
        'utility1_slot' => 'string',
        'utility1_slot_quantity' => 'integer',
        'utility2_slot' => 'string',
        'utility2_slot_quantity' => 'integer',
        'task' => 'string',
        'task_type' => 'string',
        'task_progress' => 'integer',
        'task_total' => 'integer',
        'inventory_max_items' => 'integer',
    ];

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function fights(): HasMany
    {
        return $this->hasMany(Fight::class);
    }

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupaion::class);
    }

    public function refetch(): static
    {
        return app(ArtifactsService::class)
            ->getCharacter($this->name)
            ->getModel();
    }

    public function isAt(int|Map $x, ?int $y = null): bool
    {
        if ($x instanceof Map) {
            $y = $x->y;
            $x = $x->x;
        }

        return $this->x === $x && $this->y === $y;
    }

    public function move(int|Map $x, ?int $y = null): ActionMoveData
    {
        if ($x instanceof Map) {
            $y = $x->y;
            $x = $x->x;
        }

        return app(ArtifactsService::class)->actionMove($this->name, $x, $y);
    }

    public function fight(): ActionFightData
    {
        return app(ArtifactsService::class)->actionFight($this->name);
    }

    public function gather(): ActionGatheringData
    {
        return app(ArtifactsService::class)->actionGathering($this->name);
    }

    public function rest(): ActionRestData
    {
        if ($this->is_healthy) {
            return $this;
        }

        return app(ArtifactsService::class)->actionRest($this->name);
    }

    public function hasInInventory(
        int|Item|SimpleItemData|string $item,
        int $quantity = 1
    ): bool {
        switch (true) {
            case is_int($item):
                $item = Item::find($item)->code;
                break;
            case $item instanceof Item:
                $item = $item->code;
                break;
            case $item instanceof SimpleItemData:
                $quantity = $item->quantity;
                $item = $item->code;
                break;
        }

        return $this->inventoryItems()
            ->where('code', $item)
            ->where('quantity', '>=', $quantity)
            ->exists();
    }

    public function hasSkillLevel(
        Craft|string $skill,
        ?int $level = null
    ): bool {
        if ($skill instanceof Craft) {
            $level = $skill->level;
            $skill = $skill->skill;
        }

        return $this->{$skill . '_level'} >= $level;
    }

    protected function isHealthy(): Attribute
    {
        return Attribute::get(
            fn (): bool => $this->refresh()->hp === $this->max_hp
        );
    }
}
