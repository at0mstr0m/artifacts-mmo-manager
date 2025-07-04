<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\ArtifactsService;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(ArtifactsService::class)->getAllItems(all: true);
    }
}
