<?php

declare(strict_types=1);

use Konnec\Helpers\Actions\TableName;

it('builds the table name from the configured prefix', function () {
    expect(TableName::handle())->toBe('konnec_logs');
});

it('honours a custom prefix', function () {
    config(['konnec-helpers.log_table_prefix' => 'acme']);

    expect(TableName::handle())->toBe('acme_logs');
});
