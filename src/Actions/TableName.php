<?php

namespace Konnec\Helpers\Actions;

use Konnec\Helpers\Traits\Actionable;

/**
 * Generate the table name for the log entries.
 */
class TableName
{
    use Actionable;

    public function run(): string
    {
        $prefix = config('konnec-helpers.log_table_prefix', '');

        return implode('_', [$prefix, 'logs']);
    }
}
