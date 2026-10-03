<?php

use Tests\Fixtures\Greeter;

it('resolves the action through the container', function () {
    expect(Greeter::make())->toBeInstanceOf(Greeter::class);
});

it('uses a container binding when one is registered', function () {
    $fake = new class extends Greeter
    {
        public function run(string $name, string $greeting = 'Hello'): string
        {
            return 'faked';
        }
    };
    app()->instance(Greeter::class, $fake);

    expect(Greeter::handle('Ada'))->toBe('faked');
});

it('forwards arguments to run()', function () {
    expect(Greeter::handle('Ada'))->toBe('Hello, Ada')
        ->and(Greeter::handle('Ada', 'Hi'))->toBe('Hi, Ada');
});
