<?php

declare(strict_types=1);

namespace App\Traits\Character;

use App\Actions\EvaluateFittingItemSlot;
use App\Data\Responses\ActionAcceptNewTask;
use App\Data\Responses\ActionBuyBankExpansionData;
use App\Data\Responses\ActionChangeSkinData;
use App\Data\Responses\ActionCompleteTaskData;
use App\Data\Responses\ActionCraftingData;
use App\Data\Responses\ActionDepositBankData;
use App\Data\Responses\ActionDepositBankGoldData;
use App\Data\Responses\ActionEquipItemData;
use App\Data\Responses\ActionFightData;
use App\Data\Responses\ActionGatheringData;
use App\Data\Responses\ActionGeBuyItemData;
use App\Data\Responses\ActionMoveData;
use App\Data\Responses\ActionRestData;
use App\Data\Responses\ActionTaskTradeData;
use App\Data\Schemas\SimpleItemData;
use App\Enums\CharacterSkins;
use App\Exceptions\SlotOccupiedException;
use App\Models\Character;
use App\Models\InventoryItem;
use App\Models\Item;
use App\Models\Map;
use App\Services\ArtifactsService;
use Illuminate\Support\Str;

/**
 * @mixin Character
 */
trait HasCharacterActions
{
    public function refetch(): static
    {
        return app(ArtifactsService::class)
            ->getCharacter($this->name)
            ->getModel();
    }

    public function moveTo(int|Map $x, ?int $y = null): ActionMoveData
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

    public function craft(
        Item|string $item,
        int $quantity = 1
    ): ActionCraftingData {
        if ($item instanceof Item) {
            $item = $item->code;
        }

        return app(ArtifactsService::class)->actionCrafting(
            $this->name,
            $item,
            $quantity
        );
    }

    public function rest(): ActionRestData
    {
        if ($this->is_healthy) {
            return $this;
        }

        return app(ArtifactsService::class)->actionRest($this->name);
    }

    public function unequip(string $slot, int $quantity = 1): ActionEquipItemData
    {
        $slot = Str::beforeLast($slot, '_slot');

        return app(ArtifactsService::class)
            ->actionUnequipItem($this->name, $slot, $quantity);
    }

    public function equip(
        InventoryItem|Item|string $item,
        int $quantity = 1,
        ?string $slot = null,
    ): ActionEquipItemData {
        switch (true) {
            case is_string($item):
                $item = Item::findByCode($item);
                break;
            case $item instanceof InventoryItem:
                $quantity = $quantity ?: $item->quantity;
                $item = $item->item;
                break;
        }

        if ($slot === null) {
            /** @var false|string|null */
            $slot = EvaluateFittingItemSlot::run($this, $item);

            switch (true) {
                case $slot === false:
                    throw new SlotOccupiedException();
                case $slot === null:
                    throw new \Exception('Item does not fit into any slot');
                case is_array($slot):
                    $slot = $slot[0];
                    $response = $this->unequip($slot, $quantity);
                    sleep((int) ceil(now()->diffInSeconds($response->cooldown->expiresAt)));
            }
        }

        $slot = Str::beforeLast($slot, '_slot');

        return app(ArtifactsService::class)
            ->actionEquipItem($this->name, $slot, $item->code, $quantity);
    }

    public function acceptNewTask(): ActionAcceptNewTask
    {
        return app(ArtifactsService::class)->actionAcceptNewTask($this->name);
    }

    public function tradeTaskItems(
        null|int|InventoryItem|Item|SimpleItemData|string $item = null,
        int $quantity = 0,
    ): ActionTaskTradeData {
        $item ??= $this->inventoryItems()->firstWhere('code', $this->task);

        switch (true) {
            case $item instanceof SimpleItemData:
            case $item instanceof InventoryItem:
                $quantity = $quantity ?: min(
                    $item->quantity,
                    $this->task_total - $this->task_progress
                );
                $item = $item->code;
                break;
            default:
                [$item, $quantity] = $this->extractItemAndQuantity(
                    $item,
                    $quantity
                );
        }

        $quantity = $quantity ?: ($this->task_total - $this->task_progress);

        return app(ArtifactsService::class)
            ->actionTaskTrade($this->name, $item, $quantity);
    }

    public function completeTask(): ActionCompleteTaskData
    {
        return app(ArtifactsService::class)->actionCompleteTask($this->name);
    }

    public function depositGold(int $quantity = 0): ActionDepositBankGoldData
    {
        $quantity = $quantity ?: $this->gold;

        return app(ArtifactsService::class)
            ->actionDepositBankGold($this->name, $quantity);
    }

    public function withdrawGold(int $quantity): ActionDepositBankGoldData
    {
        return app(ArtifactsService::class)
            ->actionWithdrawBankGold($this->name, $quantity);
    }

    public function buyBankExpansion(): ActionBuyBankExpansionData
    {
        return app(ArtifactsService::class)
            ->actionBuyBankExpansion($this->name);
    }

    public function depositItem(
        int|InventoryItem|Item|SimpleItemData|string $item,
        int $quantity = 0
    ): ActionDepositBankData {
        [$item, $quantity] = $this->extractItemAndQuantity($item, $quantity);
        $quantity = $quantity ?: $this->countInInventory($item);

        return app(ArtifactsService::class)
            ->actionDepositBank($this->name, $item, $quantity);
    }

    public function withdrawItem(
        int|InventoryItem|Item|SimpleItemData|string $item,
        int $quantity = 0
    ): ActionDepositBankData {
        [$item, $quantity] = $this->extractItemAndQuantity($item, $quantity);

        return app(ArtifactsService::class)
            ->actionWithdrawBank($this->name, $item, $quantity);
    }

    public function createSellOrder(
        int|InventoryItem|Item|SimpleItemData|string $item,
        int $quantity = 0,
        int $price = 1,
    ): ActionGeBuyItemData {
        [$item, $quantity] = $this->extractItemAndQuantity($item, $quantity);
        $quantity = $quantity ?: $this->countInInventory($item);

        return app(ArtifactsService::class)
            ->actionGeCreateSellOrder($this->name, $item, $quantity, $price);
    }

    public function changeSkin(CharacterSkins $skin): ActionChangeSkinData
    {
        return app(ArtifactsService::class)
            ->actionChangeSkin($this->name, $skin);
    }

    /**
     * @return (int|string)[]
     */
    private function extractItemAndQuantity(
        int|InventoryItem|Item|SimpleItemData|string $item,
        int $quantity = 0
    ): array {
        switch (true) {
            case is_int($item):
                $item = Item::find($item, ['code'])->code;
                break;
            case $item instanceof Item:
                $item = $item->code;
                break;
            case $item instanceof SimpleItemData:
            case $item instanceof InventoryItem:
                $quantity = $quantity ?: $item->quantity;
                $item = $item->code;
                break;
        }

        return [$item, $quantity];
    }
}
