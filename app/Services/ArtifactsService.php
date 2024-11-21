<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Responses\ActionAcceptNewTask;
use App\Data\Responses\ActionBuyBankExpansionData;
use App\Data\Responses\ActionCompleteTaskData;
use App\Data\Responses\ActionCraftingData;
use App\Data\Responses\ActionDeleteItemData;
use App\Data\Responses\ActionDepositBankData;
use App\Data\Responses\ActionDepositBankGoldData;
use App\Data\Responses\ActionEquipItemData;
use App\Data\Responses\ActionFightData;
use App\Data\Responses\ActionGatheringData;
use App\Data\Responses\ActionGeBuyItemData;
use App\Data\Responses\ActionGeCancelSellOrderData;
use App\Data\Responses\ActionMoveData;
use App\Data\Responses\ActionRecyclingData;
use App\Data\Responses\ActionRestData;
use App\Data\Responses\ActionTaskCancelData;
use App\Data\Responses\ActionTaskExchangeData;
use App\Data\Responses\ActionTaskTradeData;
use App\Data\Responses\ActionUseItemData;
use App\Data\Responses\GetAccountDetailsData;
use App\Data\Responses\GetBankDetailsData;
use App\Data\Responses\GetStatusData;
use App\Data\Schemas\AchievementData;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\EventData;
use App\Data\Schemas\GrandExchangeItemData;
use App\Data\Schemas\HistoricSellOrderData;
use App\Data\Schemas\ItemData;
use App\Data\Schemas\LogData;
use App\Data\Schemas\MapData;
use App\Data\Schemas\MonsterData;
use App\Data\Schemas\ResourceData;
use App\Data\Schemas\SellOrderData;
use App\Data\Schemas\SimpleItemData;
use App\Data\Schemas\TaskData;
use App\Data\Schemas\TaskRewardData;
use App\Enums\RateLimitTypes;
use App\Traits\MakesRequests;
use Illuminate\Support\Collection;

class ArtifactsService
{
    use MakesRequests;

    private const MAX_PER_PAGE = 100;

    private string $token;

    public function __construct()
    {
        $this->token = env('ARTIFACTS_TOKEN');
    }

    public function getStatus(): GetStatusData
    {
        return GetStatusData::from($this->get());
    }

    /*
     * #########################################################################
     * My characters
     * #########################################################################
     */

    public function actionMove(string $name, int $x, int $y): ActionMoveData
    {
        return ActionMoveData::from(
            $this->post(
                "my/{$name}/action/move",
                RateLimitTypes::ACTIONS,
                ['x' => $x, 'y' => $y]
            )
        );
    }

    public function actionRest(string $name): ActionRestData
    {
        return ActionRestData::from(
            $this->post(
                "my/{$name}/action/rest",
                RateLimitTypes::ACTIONS,
            )
        );
    }

    public function actionEquipItem(
        string $name,
        string $slot,
        string $itemCode,
        int $quantity = 1
    ): ActionEquipItemData {
        return ActionEquipItemData::from(
            $this->post(
                "my/{$name}/action/equip",
                RateLimitTypes::ACTIONS,
                [
                    'slot' => $slot,
                    'code' => $itemCode,
                    'quantity' => $quantity,
                ]
            )
        );
    }

    public function actionUnquipItem(
        string $name,
        string $slot,
        string $itemCode,
        int $quantity = 1
    ): ActionEquipItemData {
        return ActionEquipItemData::from(
            $this->post(
                "my/{$name}/action/unequip",
                RateLimitTypes::ACTIONS,
                [
                    'slot' => $slot,
                    'code' => $itemCode,
                    'quantity' => $quantity,
                ]
            )
        );
    }

    public function actionUseItem(
        string $name,
        string $itemCode,
        int $quantity = 1
    ): ActionUseItemData {
        return ActionUseItemData::from(
            $this->post(
                "my/{$name}/action/use",
                RateLimitTypes::ACTIONS,
                [
                    'code' => $itemCode,
                    'quantity' => $quantity,
                ]
            )
        );
    }

    public function actionFight(string $name): ActionFightData
    {
        return ActionFightData::from(
            $this->post("my/{$name}/action/fight", RateLimitTypes::ACTIONS)
        );
    }

    public function actionGathering(string $name): ActionGatheringData
    {
        return ActionGatheringData::from(
            $this->post("my/{$name}/action/gathering", RateLimitTypes::ACTIONS)
        );
    }

    public function actionCrafting(
        string $name,
        string $itemCode,
        int $quantity = 1
    ): ActionCraftingData {
        return ActionCraftingData::from(
            $this->post(
                "my/{$name}/action/crafting",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    public function actionDepositBankGold(
        string $name,
        int $quantity
    ): ActionDepositBankGoldData {
        return ActionDepositBankGoldData::from(
            $this->post(
                "my/{$name}/action/bank/deposit/gold",
                RateLimitTypes::ACTIONS,
                ['quantity' => $quantity]
            )
        );
    }

    public function actionDepositBank(
        string $name,
        string $itemCode,
        int $quantity
    ): ActionDepositBankData {
        return ActionDepositBankData::from(
            $this->post(
                "my/{$name}/action/bank/deposit",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    public function actionWithdrawBank(
        string $name,
        string $itemCode,
        int $quantity
    ): ActionDepositBankData {
        return ActionDepositBankData::from(
            $this->post(
                "my/{$name}/action/bank/withdraw",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    public function actionWithdrawBankGold(
        string $name,
        int $quantity
    ): ActionDepositBankGoldData {
        return ActionDepositBankGoldData::from(
            $this->post(
                "my/{$name}/action/bank/withdraw/gold",
                RateLimitTypes::ACTIONS,
                ['quantity' => $quantity]
            )
        );
    }

    public function actionBuyBankExpansion(
        string $name
    ): ActionBuyBankExpansionData {
        return ActionBuyBankExpansionData::from(
            $this->post(
                "my/{$name}/action/bank/buy_expansion",
                RateLimitTypes::ACTIONS
            )
        );
    }

    public function actionRecycling(
        string $name,
        string $itemCode,
        int $quantity
    ): ActionRecyclingData {
        return ActionRecyclingData::from(
            $this->post(
                "my/{$name}/action/recycling",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    public function actionGeBuyItem(
        string $name,
        string $itemCode,
        int $quantity
    ): ActionGeBuyItemData {
        return ActionGeBuyItemData::from(
            $this->post(
                "my/{$name}/action/grandexchange/buy",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    public function actionGeCreateSellOrder(
        string $name,
        string $itemCode,
        int $quantity,
        int $price,
    ): ActionGeBuyItemData {
        return ActionGeBuyItemData::from(
            $this->post(
                "my/{$name}/action/grandexchange/sell",
                RateLimitTypes::ACTIONS,
                [
                    'code' => $itemCode,
                    'quantity' => $quantity,
                    'price' => $price,
                ]
            )
        );
    }

    public function actionGeCancelSellOrder(
        string $name,
        string $identifier,
    ): ActionGeCancelSellOrderData {
        return ActionGeCancelSellOrderData::from(
            $this->post(
                "my/{$name}/action/grandexchange/cancel",
                RateLimitTypes::ACTIONS,
                ['id' => $identifier]
            )
        );
    }

    public function actionCompleteTask(string $name): ActionCompleteTaskData
    {
        return ActionCompleteTaskData::from(
            $this->post(
                "my/{$name}/action/task/complete",
                RateLimitTypes::ACTIONS,
            )
        );
    }

    public function actionTaskExchange(string $name): ActionTaskExchangeData
    {
        return ActionTaskExchangeData::from(
            $this->post(
                "my/{$name}/action/task/exchange",
                RateLimitTypes::ACTIONS,
            )
        );
    }

    public function actionAcceptNewTask(string $name): ActionAcceptNewTask
    {
        return ActionAcceptNewTask::from(
            $this->post(
                "my/{$name}/action/task/new",
                RateLimitTypes::ACTIONS,
            )
        );
    }

    public function actionTaskTrade(
        string $name,
        string $itemCode,
        int $quantity
    ): ActionTaskTradeData {
        return ActionTaskTradeData::from(
            $this->post(
                "my/{$name}/action/task/trade",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    public function actionTaskCancel(string $name): ActionTaskCancelData
    {
        return ActionTaskCancelData::from(
            $this->post(
                "my/{$name}/action/task/cancel",
                RateLimitTypes::ACTIONS,
            )
        );
    }

    public function actionDeleteItem(
        string $name,
        string $itemCode,
        int $quantity
    ): ActionDeleteItemData {
        return ActionDeleteItemData::from(
            $this->post(
                "my/{$name}/action/delete",
                RateLimitTypes::ACTIONS,
                ['code' => $itemCode, 'quantity' => $quantity]
            )
        );
    }

    /**
     * @return Collection<LogData>
     */
    public function getAllCharactersLogs(): Collection
    {
        return LogData::collection(
            // not more than 100 logs available
            $this->get('my/logs', RateLimitTypes::DATA, ['size' => 100])
        );
    }

    /**
     * @return Collection<CharacterData>
     */
    public function getMyCharacters(): Collection
    {
        return CharacterData::collection(
            $this->get('my/characters', RateLimitTypes::DATA)
        );
    }

    /*
     * #########################################################################
     * My account
     * #########################################################################
     */

    public function getBankDetails(): GetBankDetailsData
    {
        return GetBankDetailsData::from($this->get('my/bank'));
    }

    /**
     * @return Collection<SimpleItemData>
     */
    public function getBankItems(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('my/bank/items', RateLimitTypes::DATA, $query);
        $data = SimpleItemData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    /**
     * @return Collection<SellOrderData>
     */
    public function getGeSellOrders(
        ?string $itemCode = null,
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $arguments = $itemCode ? ['code' => $itemCode] : [];
        $query = [
            ...static::paginationParams($perPage, $page, $all),
            ...$arguments,
        ];
        $response = $this->get('my/grandexchange/orders', RateLimitTypes::DATA, $query);
        $data = SellOrderData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage, $arguments)
            : $data;
    }

    /**
     * @return Collection<HistoricSellOrderData>
     */
    public function getGeSellHistory(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('my/grandexchange/history', RateLimitTypes::DATA, $query);
        $data = HistoricSellOrderData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getAccountDetails(): GetAccountDetailsData
    {
        return GetAccountDetailsData::from($this->get('my/details'));
    }

    /*
     * #########################################################################
     * Character
     * #########################################################################
     */

    public function createCharacter(string $name, string $skin): CharacterData
    {
        return CharacterData::from(
            $this->post(
                'characters/create',
                RateLimitTypes::DATA,
                ['name' => $name, 'skin' => $skin]
            )
        );
    }

    public function deleteCharacter(string $name): CharacterData
    {
        return CharacterData::from(
            $this->post(
                'characters/delete',
                RateLimitTypes::DATA,
                ['name' => $name]
            )
        );
    }

    public function getCharacter(string $name): CharacterData
    {
        return CharacterData::from(
            $this->get("characters/{$name}", RateLimitTypes::DATA)
        );
    }

    /*
     * #########################################################################
     * Maps
     * #########################################################################
     */

    /**
     * @return Collection<MapData>
     */
    public function getAllMaps(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('maps', RateLimitTypes::DATA, $query);
        $data = MapData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getMap(int $x, int $y): MapData
    {
        return MapData::from($this->get("maps/{$x}/{$y}", RateLimitTypes::DATA));
    }

    /*
     * #########################################################################
     * Items
     * #########################################################################
     */

    /**
     * @return Collection<ItemData>
     */
    public function getAllItems(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('items', RateLimitTypes::DATA, $query);
        $data = ItemData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getItem(string $code): ItemData
    {
        return ItemData::from($this->get("items/{$code}", RateLimitTypes::DATA));
    }

    /*
     * #########################################################################
     * Monsters
     * #########################################################################
     */

    /**
     * @return Collection<MonsterData>
     */
    public function getAllMonsters(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('monsters', RateLimitTypes::DATA, $query);
        $data = MonsterData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getMonster(string $code): MonsterData
    {
        return MonsterData::from($this->get("monsters/{$code}", RateLimitTypes::DATA));
    }

    /*
     * #########################################################################
     * Resources
     * #########################################################################
     */

    /**
     * @return Collection<ResourceData>
     */
    public function getAllResources(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('resources', RateLimitTypes::DATA, $query);
        $data = ResourceData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getResource(string $code): ResourceData
    {
        return ResourceData::from($this->get("resources/{$code}", RateLimitTypes::DATA));
    }

    /*
     * #########################################################################
     * Events
     * #########################################################################
     */

    /**
     * @return Collection<EventData>
     */
    public function getAllEvents(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('events/active', RateLimitTypes::DATA, $query);
        $data = EventData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    /*
     * #########################################################################
     * Grand Exchange
     * #########################################################################
     */

    /**
     * @return Collection<GrandExchangeItemData>
     */
    public function getAllGeItem(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('ge', RateLimitTypes::DATA, $query);
        $data = GrandExchangeItemData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getGeItem(string $code): GrandExchangeItemData
    {
        return GrandExchangeItemData::from($this->get("ge/{$code}", RateLimitTypes::DATA));
    }

    /*
     * #########################################################################
     * Tasks
     * #########################################################################
     */

    /**
     * @return Collection<TaskData>
     */
    public function getAllTasks(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('tasks/list', RateLimitTypes::DATA, $query);
        $data = TaskData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getTask(string $code): TaskData
    {
        return TaskData::from(
            $this->get("tasks/list/{$code}", RateLimitTypes::DATA)
        );
    }

    /**
     * @return Collection<TaskRewardData>
     */
    public function getAllTaskRewards(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('tasks/rewards', RateLimitTypes::DATA, $query);
        $data = TaskRewardData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getTaskReward(string $code): TaskRewardData
    {
        return TaskRewardData::from(
            $this->get("tasks/rewards/{$code}", RateLimitTypes::DATA)
        );
    }

    /*
     * #########################################################################
     * Achievements
     * #########################################################################
     */

    /**
     * @return Collection<AchievementData>
     */
    public function getAllAchievements(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $query = static::paginationParams($perPage, $page, $all);
        $response = $this->get('achievements', RateLimitTypes::DATA, $query);
        $data = AchievementData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }

    public function getAchievement(string $code): AchievementData
    {
        return AchievementData::from(
            $this->get("achievements/{$code}", RateLimitTypes::DATA)
        );
    }
}
