<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumUtils
{
    // https://stackoverflow.com/a/71680007/13128152
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    // https://stackoverflow.com/a/71680007/13128152
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    // https://stackoverflow.com/a/71680007/13128152
    public static function toArray(): array
    {
        return array_combine(static::names(), static::values());
    }

    public static function isValid(string $needle): bool
    {
        return in_array($needle, static::values(), true);
    }

    // https://stackoverflow.com/a/71002493
    public static function fromName(self|string $name): static
    {
        if ($name instanceof static) {
            return $name;
        }
        foreach (static::cases() as $case) {
            if ($name === $case->name) {
                return $case;
            }
        }

        throw new \ValueError("{$name} is not a valid name for enum " . static::class);
    }

    public static function fromValue(self|string $value): static
    {
        if ($value instanceof static) {
            return $value;
        }
        foreach (static::cases() as $case) {
            if ($value === $case->value) {
                return $case;
            }
        }

        throw new \ValueError("{$value} is not a valid value for enum " . static::class);
    }

    public static function random(): static
    {
        $cases = static::cases();

        return static::fromName($cases[array_rand($cases)]);
    }
}
