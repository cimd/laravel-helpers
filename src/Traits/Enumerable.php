<?php

declare(strict_types=1);

namespace Konnec\Helpers\Traits;

trait Enumerable
{
    public static function toArray(): array
    {
        return collect(self::cases())->map(fn (self $case) => [$case->name => $case->value])->toArray();
    }

    public static function fromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if (strtoupper((string) $case->name) === strtoupper($name)) {
                return $case;
            }
        }

        return null;
    }

    public static function fromValue(mixed $value): ?self
    {
        return self::tryFrom($value);
    }

    /**
     * Returns the label of the case in a human-readable format.
     */
    public function label(): string
    {
        return ucwords(
            strtolower(
                str_replace('_', ' ', $this->name)
            )
        );
    }

    /**
     * Returns the label of the case in a human-readable format, in lowercase.
     */
    public function label_lowerc(): string
    {
        return strtolower(str_replace('_', ' ', $this->name));
    }
}
