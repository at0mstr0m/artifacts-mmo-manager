<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\ArtifactsService;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api = app(ArtifactsService::class);

        $api->getAllResources(all: true);
        $api->getAllTaskRewards(all: true);
    }
}
