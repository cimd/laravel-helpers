<?php

namespace Konnec\Helpers\Loggers;

use Illuminate\Support\Facades\DB;
use Konnec\Helpers\Actions\TableName;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class MySQLLoggingHandler extends AbstractProcessingHandler
{
    /**
     * Reference:
     * https://github.com/markhilton/monolog-mysql/blob/master/src/Logger/Monolog/Handler/MysqlHandler.php
     */
    public function __construct($level = Level::Debug, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array|LogRecord $record): void
    {
        $data = [
            'message' => $record['message'],
            'context' => json_encode($record['context'], JSON_THROW_ON_ERROR),
            'level' => $record['level'],
            'level_name' => $record['level_name'],
            'channel' => $record['channel'],
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra' => json_encode($record['extra'], JSON_THROW_ON_ERROR),
            'formatted' => $record['formatted'],
            'remote_addr' => isset($_SERVER['REMOTE_ADDR']) ? ip2long($_SERVER['REMOTE_ADDR']) : null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ];
        DB::connection()->table(TableName::handle())->insert($data);
    }
}
