<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\UpdateBankDepositsAction;
use Illuminate\Database\Seeder;

class BankItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UpdateBankDepositsAction::run();
    }
}
