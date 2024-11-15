<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\ArtifactsService;
use Illuminate\Database\Seeder;

class GrandExchangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api = app(ArtifactsService::class);

        $api->getGeSellHistory(all: true);
        $api->getGeSellOrders(all: true);
    }
}
