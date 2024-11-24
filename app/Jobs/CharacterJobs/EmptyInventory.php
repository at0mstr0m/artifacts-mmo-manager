<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\Schemas\SimpleItemData;
use App\Jobs\CharacterJob;
use App\Models\InventoryItem;
use App\Models\Map;
use App\Services\ArtifactsService;
use Illuminate\Support\Collection;

class EmptyInventory extends CharacterJob
{
    /**
     * @param Collection<SimpleItemData>|null $keep
     */
    public function __construct(
        protected int $characterId,
        protected ?Collection $keep = null,
    ) {
        $this->constructorArguments = compact('characterId', 'keep');
    }

    protected function handleCharacter(): void
    {
        $this->ensureIsAtBank();

        $this->depositGold();

        $this->buyBankExpansion();

        $this->depositItems();

        $this->dispatchNextJob();
    }

    private function ensureIsAtBank(): void
    {
        if ($this->character->location->content_type === 'bank') {
            return;
        }

        /** @var Map */
        $bankLocation = Map::firstWhere('content_type', 'bank');

        if ($this->character->isAt($bankLocation)) {
            $this->log('At bank, moving to bank.');
            $this->character->moveTo($bankLocation->x, $bankLocation->y);
        } else {
            $moveData = $this->character->moveTo($bankLocation);
            $this->log('Moving to bank.');
            $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

            $this->end();
        }
    }

    private function depositGold(): void
    {
        if ($this->character->gold === 0) {
            $this->log('No gold to deposit');

            return;
        }

        $this->log("Depositing {$this->character->gold} gold");
        $data = $this->character->depositGold();
        $this->selfDispatch()->delay($data->cooldown->expiresAt);

        $this->end();
    }

    private function buyBankExpansion(): void
    {
        $bankDetails = app(ArtifactsService::class)->getBankDetails();
        $nextExpansionCost = $bankDetails->nextExpansionCost;
        $this->log("Next bank expansion costs {$nextExpansionCost} gold");

        if ($nextExpansionCost > $bankDetails->gold) {
            $this->log('Not enough gold to buy expansion');

            return;
        }

        $this->log('withdrawing gold to buy expansion');
        $withdrawData = $this->character->withdrawGold($nextExpansionCost);
        $this->character = $withdrawData->character->getModel();

        sleep($withdrawData->cooldown->totalSeconds + 2);

        $this->log('Buying bank expansion');
        $buyExpansionData = $this->character->buyBankExpansion();

        $this->selfDispatch()->delay($buyExpansionData->cooldown->expiresAt);
        $this->end();
    }

    private function depositItems(): void
    {
        $this->log('Depositing items');

        $this->character
            ->inventoryItems()
            ->where('quantity', '>', 0)
            ->each(
                fn (InventoryItem $item) => $this->handleInventoryItem($item)
            );
    }

    private function keepQuantity(InventoryItem $item): int
    {
        return (int) $this->keep
            ?->firstWhere(
                fn (SimpleItemData $itemData) => $itemData->code === $item->code
            )
            ?->quantity;
    }

    private function handleInventoryItem(InventoryItem $item): void
    {
        $keepQuantity = $this->keepQuantity($item);

        if (! $keepQuantity) {
            $this->log("Depositing {$item->quantity} {$item->code}");
            $data = $this->character->depositItem($item);
            $this->selfDispatch()->delay($data->cooldown->expiresAt);

            $this->end();
        }

        if ($item->quantity <= $keepQuantity) {
            $this->log("Keeping {$item->quantity} {$item->name}");

            return;
        }

        $toDeposit = $item->quantity - $keepQuantity;

        $this->log("Depositing {$toDeposit} {$item->name}");
        $data = $this->character->depositItem($item, $toDeposit);
        $this->end();
    }
}
