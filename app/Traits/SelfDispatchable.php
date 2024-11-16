<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Queue\Queueable;

/**
 * @mixin Queueable
 */
trait SelfDispatchable
{
    protected array $constructorArguments = [];

    protected function selfDispatch(array $arguments = []): PendingDispatch
    {
        return static::dispatch(...[
            ...$this->constructorArguments,
            ...$arguments,
        ]);
    }
}
