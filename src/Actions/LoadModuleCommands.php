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

    /**
     * @return array<int, string>
     */
    public function run(): array
    {
        //      Normalize search path between windows and linux platforms
        $searchPath = (PHP_OS === 'WINNT') ? 'modules\*\Commands\*.php' : 'modules/*/Commands/*.php';

        return collect(
            glob(base_path($searchPath)) ?: []
        )->map(function ($item) {
            $withoutPrefix = (PHP_OS === 'WINNT') ? Str::after($item, base_path() . '\\modules\\') : Str::after($item, base_path() . '/modules/');
            $withoutSuffix = Str::beforeLast($withoutPrefix, '.php');

            $partial = 'Modules' . DIRECTORY_SEPARATOR . $withoutSuffix;

            return str_replace('/', '\\', $partial);
        })->toArray();
    }
}
