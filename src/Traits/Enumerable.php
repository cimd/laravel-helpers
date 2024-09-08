<?php

declare(strict_types=1);

namespace Konnec\LaravelHelpers\Traits;

trait Enumerable
{
    public static function toArray(): array
    {
        return collect(self::cases())->map(fn (self $case) => [$case->name => $case->value])->toArray();
    }

    public static function fromName(string $name): \Illuminate\Support\Collection
    {
        return collect(self::toArray())->filter(function (mixed $value, string $key) use ($name) {
            return $value == $name;
        });
    }

    public static function fromValue(int $value): self
    {
        return self::tryFrom($value);
    }

    public function label(): string
    {
        return ucwords(
            strtolower(
                str_replace('_', ' ', $this->name)
            )
        );
    }
}
