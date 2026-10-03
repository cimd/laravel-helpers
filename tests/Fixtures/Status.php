<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Konnec\Helpers\Traits\Enumerable;

enum Status: int
{
    use Enumerable;

    case ACTIVE = 1;
    case IN_PROGRESS = 2;
    case ON_HOLD = 3;
}
