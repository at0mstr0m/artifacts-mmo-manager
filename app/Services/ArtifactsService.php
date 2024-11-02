<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Responses\GetBankDetailsData;
use App\Data\Responses\GetItemData;
use App\Data\Responses\GetStatusData;
use App\Data\Schemas\EventData;
use App\Data\Schemas\ItemData;
use App\Data\Schemas\MapData;
use App\Data\Schemas\MonsterData;
use App\Data\Schemas\ResourceData;
use App\Data\Schemas\SimpleItemData;
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

    public function getItem(string $code): GetItemData
    {
        return GetItemData::from($this->get("items/{$code}", RateLimitTypes::DATA));
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
        $response = $this->get('events', RateLimitTypes::DATA, $query);
        $data = EventData::collection($response);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }
}
