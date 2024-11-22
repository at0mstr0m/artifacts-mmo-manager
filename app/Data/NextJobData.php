<?php

declare(strict_types=1);

namespace App\Data;

use App\Traits\SelfDispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NextJobData extends Data
{
    public function __construct(
        public Queueable|string $jobClass,
        public array $jobParameters,
    ) {}

    /**
     * @return SelfDispatchable&ShouldQueue
     */
    public function makeJob(): ShouldQueue
    {
        return new $this->jobClass(...$this->jobParameters);
    }
}
