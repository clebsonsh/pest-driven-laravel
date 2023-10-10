<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('should not found unreleased course', function () {
    $course = Course::factory()->create();

    // Act & Assert
    get(route('course-details', $course))
        ->assertNotFound();
});

it('show course details', function () {
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('course-details', $course))
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/{$course->image_name}"));
});

it('show course videos count', function () {
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3))
        ->create();

    // Act & Assert
    get(route('course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
});
