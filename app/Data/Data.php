<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

abstract class Data
{
    public static function collection(array|Arrayable|Response $data): Collection
    {
        if ($data instanceof Response) {
            $data = $data->json('data');
        }

        return collect($data)->map(fn (array $item) => static::from($item));
    }

    public static function from(null|array|Arrayable|Response $data): ?static
    {
        if ($data instanceof Response) {
            $data = $data->json('data');
        }

        return $data === null
            ? $data
            : new static(...collect($data)->camelKeys()->toArray());
    }
}
