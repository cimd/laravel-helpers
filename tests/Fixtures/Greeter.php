<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Konnec\Helpers\Traits\Actionable;

class Greeter
{
    use Actionable;

    public function run(string $name, string $greeting = 'Hello'): string
    {
        return "{$greeting}, {$name}";
    }
}
