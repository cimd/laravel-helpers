<?php

namespace Konnec\Helpers\Providers;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/konnec-helpers.php' => config_path('konnec-helpers.php'),
        ]);

        $this->publishesMigrations([
            __DIR__ . '/../Migrations' => database_path('migrations'),
        ]);
    }
}
