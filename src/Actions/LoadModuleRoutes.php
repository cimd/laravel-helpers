<?php

namespace Konnec\Helpers\Actions;

use Konnec\Helpers\Traits\Actionable;
use Illuminate\Support\Facades\Route;

/**
 * Generate the table name for the log entries.
 */
class LoadModuleRoutes
{
    use Actionable;

    public function run(): void
    {
        $routeFiles = collect(
            glob(base_path('modules/*/Routes/*.api.php'))
        );

        $routeFiles->each(function ($item) {
            Route::prefix('api')
                ->middleware('api')
                ->group($item);
        });
    }
}
