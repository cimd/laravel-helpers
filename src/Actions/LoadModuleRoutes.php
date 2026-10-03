<?php

namespace Konnec\Helpers\Actions;

use Illuminate\Support\Facades\Route;
use Konnec\Helpers\Traits\Actionable;

/**
 * Generate the table name for the log entries.
 */
class LoadModuleRoutes
{
    use Actionable;

    public function run(): null
    {
        $routeFiles = collect(
            glob(base_path('modules/*/Routes/*.api.php')) ?: []
        );

        $routeFiles->each(function ($item) {
            Route::prefix('api')
                ->middleware('api')
                ->group($item);
        });

        return null;
    }
}
