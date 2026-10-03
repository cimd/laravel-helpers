<?php

declare(strict_types=1);

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

it('resolves a case from its name', function () {
    expect(Status::fromName('ON_HOLD'))->toBe(Status::ON_HOLD);
});

it('matches case names case-insensitively', function () {
    expect(Status::fromName('active'))->toBe(Status::ACTIVE)
        ->and(Status::fromName('In_Progress'))->toBe(Status::IN_PROGRESS);
});

it('returns null for an unknown case name', function () {
    expect(Status::fromName('MISSING'))->toBeNull();
});

it('builds a human readable label from the case name', function () {
    expect(Status::IN_PROGRESS->label())->toBe('In Progress')
        ->and(Status::ACTIVE->label())->toBe('Active');
});

it('builds a lowercase label from the case name', function () {
    expect(Status::IN_PROGRESS->label_lowerc())->toBe('in progress')
        ->and(Status::ACTIVE->label_lowerc())->toBe('active');
});
