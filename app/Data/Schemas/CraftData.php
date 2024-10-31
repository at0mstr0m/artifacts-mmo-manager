<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Enums\Skills;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class CraftData extends Data
{
    /**
     * @param  Collection<SimpleItemData>  $items
     */
    public function __construct(
        #[WithCast(EnumCast::class)]
        public Skills $skill,
        public int $level,
        public Collection $items,
    ) {}
}
