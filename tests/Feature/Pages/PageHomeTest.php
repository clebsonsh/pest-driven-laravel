<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows courses overview', function () {
    // Arrange
    $courses = Course::factory()
        ->released()
        ->count(3)
        ->create()
        ->map->only(['title', 'description'])
        ->values();

    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText(...$courses);
});

it('shows only released courses', function () {
    // Arrange
    $releasedCourse = Course::factory()
        ->released()
        ->create();
    $notReleasedCourse = Course::factory()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText($releasedCourse->title)
        ->assertDontSeeText($notReleasedCourse->title);
});

it('shows courses by release date', function () {
    // Arrange
    $releasedCourse = Course::factory()
        ->released(now()->subDay())
        ->create();
    $latestReleasedCourse = Course::factory()
        ->released()
        ->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeInOrder([
            $latestReleasedCourse->title,
            $releasedCourse->title,
        ]);
});
