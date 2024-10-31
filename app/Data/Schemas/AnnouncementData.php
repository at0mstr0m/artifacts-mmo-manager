<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class AnnouncementData extends Data
{
    public function __construct(
        public string $message,
        #[MapInputName('created_at')]
        #[WithCast(CarbonCast::class)]
        public string $createdAt,
    ) {}
}
