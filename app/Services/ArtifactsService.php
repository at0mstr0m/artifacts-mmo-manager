<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Responses\GetBankDetailsData;
use App\Data\Responses\GetItemData;
use App\Data\Responses\GetStatusData;
use App\Data\Schemas\ItemData;
use App\Data\Schemas\MapData;
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

    // todo: implement
    // public function getBankItems(): GetBankDetailsData
    // {
    //     return GetBankDetailsData::from($this->get('my/bank/items'));
    // }

    /*
     * #########################################################################
     * Maps
     * #########################################################################
     */

    public function getMap(int $x, int $y): MapData
    {
        return MapData::from($this->get("maps/{$x}/{$y}", RateLimitTypes::DATA));
    }

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

    /*
     * #########################################################################
     * Items
     * #########################################################################
     */

    public function getItem(string $code): GetItemData
    {
        return GetItemData::from($this->get("items/{$code}", RateLimitTypes::DATA));
    }

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
}
