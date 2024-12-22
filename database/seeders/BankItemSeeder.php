<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Data\Schemas\SimpleItemData;
use App\Services\ArtifactsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class BankItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::lock(static::class, 10)->get(fn () => $this->updateBankItems());
    }

    private function updateBankItems(): void
    {
        app(ArtifactsService::class)
            ->getBankItems(all: true)
            ->each(function (SimpleItemData $data) {
                $data->getModel()->update(['deposited' => $data->quantity]);
            });
    }
}
