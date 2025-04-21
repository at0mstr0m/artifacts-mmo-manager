<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Actions\UpdateBankDepositsAction;
use App\Jobs\CharacterJob;
use App\Models\Item;
use App\Traits\InteractsWithBank;

class WithdrawFromBank extends CharacterJob
{
    use InteractsWithBank;

    public function __construct(
        protected int $characterId,
        protected string $itemCode,
        protected int $quantity = 0, // 0 if all
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'itemCode',
            'quantity'
        );
    }

    protected function handleCharacter(): void
    {
        $this->ensureIsAtBank();

        $this->withdrawItem();

        $this->dispatchNextJob();
    }

    private function withdrawItem(): void
    {
        $item = Item::findByCode($this->itemCode);

        $this->log("Withdrawing {$this->quantity} Units of {$item->name}.");
        $this->log('Updating deposited quantity...');
        UpdateBankDepositsAction::run();

        if (! $item->refresh()->deposited) {
            $this->log('No items deposited.');

            $this->dispatchNextJob();
            $this->end();
        }

        $quantity = $this->quantity
            ? min($this->quantity, $item->deposited)
            : $item->deposited;
        $this->log("Withdrawing {$quantity} Units of {$item->name}.");
        $this->character->withdrawItem($item, $quantity);
    }
}
