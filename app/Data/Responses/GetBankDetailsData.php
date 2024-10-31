<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;

class GetBankDetailsData extends Data
{
    public function __construct(
        public int $slots,
        public int $expansions,
        public int $nextExpansionCost,
        public int $gold
    ) {}
}
