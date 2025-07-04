<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\ComparisonOperators;

class ItemConditionData extends Data
{
    public function __construct(
        public string $code,
        public ComparisonOperators|string $operator,
        public int $value,
    ) {
        $this->operator = ComparisonOperators::fromValue($operator);
    }
}
