<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

trait IdentifiableByCode
{
    public static function findByCode(string|\UnitEnum $code): ?static
    {
        $code = is_string($code) ? $code : $code->value;

        return static::firstWhere('code', $code);
    }

    public static function findManyByCode(array|Arrayable $codes): Collection
    {
        $codes = is_array($codes) ? $codes : $codes->toArray();

        return static::whereIn('code', $codes)
            ->get();
    }

    public function scopeSearchByCode(
        Builder $query,
        string $search = ''
    ): Builder {
        return $query->when(
            $search ?: request('search'),
            fn (Builder $query, string $searchTerm) => $query
                ->where('code', 'like', "%{$searchTerm}%")
        );
    }
}
