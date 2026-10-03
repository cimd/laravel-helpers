<?php

namespace Konnec\Helpers\Loggers;

use Illuminate\Support\Facades\DB;
use Konnec\Helpers\Actions\TableName;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Throwable;

class MySQLLoggingHandler extends AbstractProcessingHandler
{
    /**
     * A failure here must never propagate: this handler is typically reached while reporting
     * some other exception, and if writing the log entry itself throws (e.g. the connection is
     * mid an already-aborted transaction, so any further query on it fails too), that new
     * exception gets reported the same way - which fails the same way again, and each retry's
     * message embeds the full text of the one before it. That is unbounded, near-immediate
     * growth with no circuit breaker: a single ordinary query failure escalates into gigabytes
     * of string concatenation and an OOM within seconds. Swallowing failures here is what keeps
     * "the log write itself failed" from ever becoming a second, worse incident.
     *
     * @param  array<string, mixed>|LogRecord  $record
     */
    protected function write(array|LogRecord $record): void
    {
        try {
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
        } catch (Throwable $e) {
            error_log('MySQLLoggingHandler failed to write a log entry: ' . $e->getMessage());
        }
    }
}
