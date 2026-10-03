<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Konnec\Helpers\Actions\LoadModuleCommands;
use Konnec\Helpers\Actions\LoadModuleRoutes;

beforeEach(function () {
    $this->basePath = sys_get_temp_dir() . '/konnec-modules-' . uniqid();
    File::ensureDirectoryExists($this->basePath . '/modules/Blog/Commands');
    File::ensureDirectoryExists($this->basePath . '/modules/Blog/Routes');
    File::ensureDirectoryExists($this->basePath . '/modules/Shop/Commands');
    app()->setBasePath($this->basePath);
});

afterEach(function () {
    File::deleteDirectory($this->basePath);
});

it('returns an empty list when there are no module commands', function () {
    File::deleteDirectory($this->basePath . '/modules');

    expect(LoadModuleCommands::handle())->toBe([]);
});

it('discovers module command classes', function () {
    File::put($this->basePath . '/modules/Blog/Commands/Publish.php', '<?php');
    File::put($this->basePath . '/modules/Shop/Commands/Sync.php', '<?php');

    expect(LoadModuleCommands::handle())
        ->toEqualCanonicalizing([
            'Modules\\Blog\\Commands\\Publish',
            'Modules\\Shop\\Commands\\Sync',
        ]);
});

it('registers module api routes under the api prefix', function () {
    File::put(
        $this->basePath . '/modules/Blog/Routes/blog.api.php',
        "<?php\nuse Illuminate\\Support\\Facades\\Route;\nRoute::get('/ping', fn () => 'pong');"
    );

    LoadModuleRoutes::handle();

    expect(collect(Route::getRoutes())->map->uri()->all())->toContain('api/ping');
});

it('ignores route files that are not named *.api.php', function () {
    File::put(
        $this->basePath . '/modules/Blog/Routes/web.php',
        "<?php\nuse Illuminate\\Support\\Facades\\Route;\nRoute::get('/nope', fn () => 'x');"
    );

    LoadModuleRoutes::handle();

    expect(collect(Route::getRoutes())->map->uri()->all())->not->toContain('api/nope');
});
