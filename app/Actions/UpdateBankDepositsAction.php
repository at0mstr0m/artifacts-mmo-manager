<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\Schemas\SimpleItemData;
use App\Models\Item;
use App\Services\ArtifactsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBankDepositsAction
{
    use AsAction;

    /**
     * @var Collection<SimpleItemData>
     */
    private ?Collection $data;

    /**
     * @param Collection<SimpleItemData>|null $data
     */
    public function handle(
        ?Collection $data = null,
    ): void {
        $this->data = $data ??= app(ArtifactsService::class)->getBankItems(all: true);

        Cache::lock(static::class, 10)->get(fn () => $this->updateBankItems());
    }

    private function updateBankItems(): void
    {
        DB::transaction(function () {
            // reset all deposits to 0
            Item::getQuery()->update(['deposited' => 0]);
            // update deposits
            $this->data->each(
                fn (SimpleItemData $itemData) => $itemData
                    ->getModel()
                    ->update(['deposited' => $itemData->quantity])
            );
        });
    }
}
