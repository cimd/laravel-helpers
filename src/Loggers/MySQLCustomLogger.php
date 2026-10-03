<?php

declare(strict_types=1);

namespace Konnec\Helpers\Loggers;

use Monolog\Logger;

class MySQLCustomLogger
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('MySQLLoggingHandler');

        return $logger->pushHandler(new MySQLLoggingHandler);
    }
}
