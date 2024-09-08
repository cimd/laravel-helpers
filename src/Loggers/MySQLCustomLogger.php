<?php

namespace Konnec\Helpers\Loggers;

use Monolog\Logger;

class MySQLCustomLogger
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('MySQLLoggingHandler');

        return $logger->pushHandler(new MySQLLoggingHandler);
    }
}
