<?php

namespace Konnec\Helpers\Actions;

use Illuminate\Support\Str;
use Konnec\Helpers\Traits\Actionable;

/**
 * Generate the table name for the log entries.
 */
class LoadModuleCommands
{
    use Actionable;

    public function run(): array
    {
        //      Normalize search path between windows and linux platforms
        (PHP_OS === 'WINNT') ? $searchPath = 'modules\*\Commands\*.php' : $searchPath = 'modules/*/Commands/*.php';

        $modulesCommands = collect(
            glob(base_path($searchPath))
        )->map(function ($item) {
            (PHP_OS === 'WINNT') ? $withoutPrefix = Str::after($item, base_path() . '\\modules\\') : $withoutPrefix = Str::after($item, base_path() . '/modules/');
            $withoutSuffix = Str::beforeLast($withoutPrefix, '.php');

            $partial = 'Modules' . DIRECTORY_SEPARATOR . $withoutSuffix;

            return str_replace('/', '\\', $partial);
        })->toArray();

        return $modulesCommands;
    }
}
