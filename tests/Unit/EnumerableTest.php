<?php

use Tests\Fixtures\Status;

it('maps every case to its name and value', function () {
    expect(Status::toArray())->toBe([
        ['ACTIVE' => 1],
        ['IN_PROGRESS' => 2],
        ['ON_HOLD' => 3],
    ]);
});

it('resolves a case from its value', function () {
    expect(Status::fromValue(2))->toBe(Status::IN_PROGRESS);
});

it('returns null for an unknown value', function () {
    expect(Status::fromValue(99))->toBeNull();
});

it('finds the entry matching a case name', function () {
    expect(Status::fromName('ON_HOLD')->all())->toBe([['ON_HOLD' => 3]]);
});

it('returns an empty collection for an unknown case name', function () {
    expect(Status::fromName('MISSING'))->toBeEmpty();
});

it('matches case names exactly', function () {
    expect(Status::fromName('active'))->toBeEmpty();
});

it('builds a human readable label from the case name', function () {
    expect(Status::IN_PROGRESS->label())->toBe('In Progress')
        ->and(Status::ACTIVE->label())->toBe('Active');
});
