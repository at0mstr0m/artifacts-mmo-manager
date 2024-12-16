<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Services\ArtifactsService;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api = app(ArtifactsService::class);

        $api->getAllAchievements(all: true);
        $api->getAccountAchievements(Account::first()->username, all: true);
    }
}
