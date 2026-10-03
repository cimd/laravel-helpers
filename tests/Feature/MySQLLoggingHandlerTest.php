<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Konnec\Helpers\Actions\TableName;
use Konnec\Helpers\Loggers\MySQLCustomLogger;
use Konnec\Helpers\Loggers\MySQLLoggingHandler;
use Monolog\Level;
use Monolog\Logger;

beforeEach(function () {
    $this->artisan('migrate', ['--path' => realpath(__DIR__ . '/../../src/Migrations'), '--realpath' => true])
        ->assertExitCode(0);
});

it('creates the log table through the package migration', function () {
    expect(Schema::hasTable('konnec_logs'))->toBeTrue();
});

it('writes a log record to the database', function () {
    $_SERVER['HTTP_USER_AGENT'] = 'Pest';

    $logger = (new MySQLCustomLogger)(['driver' => 'custom']);
    $logger->warning('something happened', ['id' => 7]);

    $row = DB::table(TableName::handle())->first();

    expect($row)->not->toBeNull()
        ->and($row->message)->toBe('something happened')
        ->and($row->level_name)->toBe('WARNING')
        ->and($row->channel)->toBe('MySQLLoggingHandler')
        ->and(json_decode($row->context, true))->toBe(['id' => 7])
        ->and($row->user_agent)->toBe('Pest');
});

it('respects the handler minimum level', function () {
    $logger = (new Logger('test'))->pushHandler(new MySQLLoggingHandler(Level::Error));

    $logger->info('ignored');
    $logger->error('kept');

    expect(DB::table(TableName::handle())->pluck('message')->all())->toBe(['kept']);
});

it('swallows failures instead of throwing', function () {
    Schema::drop(TableName::handle());
    $errorLog = tempnam(sys_get_temp_dir(), 'errlog');
    $previous = ini_set('error_log', $errorLog);

    $logger = (new Logger('test'))->pushHandler(new MySQLLoggingHandler);

    expect(fn () => $logger->error('cannot be stored'))->not->toThrow(Throwable::class)
        ->and(file_get_contents($errorLog))->toContain('MySQLLoggingHandler failed to write a log entry');

    ini_set('error_log', $previous === false ? '' : $previous);
    unlink($errorLog);
});
