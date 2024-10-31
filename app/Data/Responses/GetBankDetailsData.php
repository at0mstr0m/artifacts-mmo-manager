<?php

declare(strict_types=1);

namespace App\Data\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class GetBankDetailsData extends Data
{
    public function __construct(
        public int $slots,
        public int $expansions,
        #[MapInputName('next_expansion_cost')]
        public int $nextExpansionCost,
        public int $gold
    ) {}
}
