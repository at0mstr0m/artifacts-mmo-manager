<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Responses\GetBankDetailsData;
use App\Data\Responses\GetStatusData;
use App\Data\Schemas\ItemData;
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
        return GetStatusData::from($this->get()->json('data'));
    }

    /*
     * #########################################################################
     * My account
     * #########################################################################
     */

    public function getBankDetails(): GetBankDetailsData
    {
        return GetBankDetailsData::from($this->get('my/bank')->json('data'));
    }

    // todo: implement
    // public function getBankItems(): GetBankDetailsData
    // {
    //     return GetBankDetailsData::from($this->get('my/bank/items')->json('data'));
    // }

    /*
     * #########################################################################
     * Items
     * #########################################################################
     */

    public function getItems(
        int $perPage = 10,
        int $page = 1,
        bool $all = false
    ): Collection {
        if ($all) {
            $perPage = static::MAX_PER_PAGE;
            $page = 1;
        }

        $response = $this->get(
            'items',
            static::paginationParams($perPage, $page, $all)
        );

        /** @var Collection */
        $data = ItemData::collect($response->json('data'), Collection::class);

        return $all
            ? $this->getAllPagesData($data, $response, __FUNCTION__, $page, $perPage)
            : $data;
    }
}
