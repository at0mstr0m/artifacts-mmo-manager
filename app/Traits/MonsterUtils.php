<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Monster;
use Illuminate\Support\Collection;

/**
 * @mixin Monster
 */
trait MonsterUtils
{
    public function getWeaknesses(): Collection
    {
        return $this
            ->resistances()
            ->filter(fn (int $resistance) => $resistance < 0);
    }

    public function getGreatestWeaknesses(): Collection
    {
        $resistances = $this->resistances();
        $min = $resistances->min();

        return $resistances->filter(
            fn (int $resistance) => $resistance === $min
        );
    }

    public function getResistances(): Collection
    {
        return $this
            ->resistances()
            ->filter(fn (int $resistance) => $resistance > 0);
    }

    public function getGreatestResistances(): Collection
    {
        $resistances = $this->resistances();
        $max = $resistances->max();

        return $resistances->filter(
            fn (int $resistance) => $resistance === $max
        );
    }

    private function resistances(): Collection
    {
        return collect([
            'fire' => $this->res_fire,
            'earth' => $this->res_earth,
            'water' => $this->res_water,
            'air' => $this->res_air,
        ]);
    }
}
