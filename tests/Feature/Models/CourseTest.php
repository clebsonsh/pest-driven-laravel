<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Course;

uses(RefreshDatabase::class);

it('only returns release courses for released scope', function () {
    Course::factory()->released()->create();
    Course::factory()->create();

    expect(Course::released()->get())
        ->toHaveCount(1)
        ->first()->id->toBe(1);
});
