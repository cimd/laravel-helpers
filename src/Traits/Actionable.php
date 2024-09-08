<?php

namespace Konnec\Helpers\Traits;

trait Actionable
{
    public static function make()
    {
        return app(static::class);
    }

    /**
     * Action Class must implement a run method, which
     * will be called by the handle method.
     */
    public static function handle(mixed ...$arguments): mixed
    {
        return static::make()->run(...$arguments);
    }
}
