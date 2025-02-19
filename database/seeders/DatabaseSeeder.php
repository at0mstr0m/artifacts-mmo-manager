<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AccountSeeder::class,
            UserSeeder::class,
            EffectSeeder::class,
            ItemSeeder::class,
            MapSeeder::class,
            MonsterSeeder::class,
            ResourceSeeder::class,
            EventSeeder::class,
            TaskSeeder::class,
            AchievementSeeder::class,
            CharacterSeeder::class,
            GrandExchangeSeeder::class,
            BankItemSeeder::class,
        ]);
    }
}
