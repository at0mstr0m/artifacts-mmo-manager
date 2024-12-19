<?php

declare(strict_types=1);

namespace App\Traits;

use App\Jobs\CharacterJob;
use App\Models\Character;
use App\Models\Map;

/**
 * @property Character $character
 *
 * @mixin CharacterJob
 * @mixin SelfDispatchable
 */
trait InteractsWithBank
{
    protected function ensureIsAtBank(): void
    {
        if ($this->character->location->content_type === 'bank') {
            $this->log('Is currently at bank.');

            return;
        }

        /** @var Map */
        $bankLocation = Map::firstWhere('content_type', 'bank');

        if ($this->character->isAt($bankLocation)) {
            $this->log('This should not be possible, as the character is already at the bank.');

            return;
        }

        $moveData = $this->character->moveTo($bankLocation);
        $this->log('Moving to bank.');
        $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

        $this->end();
    }
}
