<?php

use App\Models\Course;

use function Pest\Laravel\get;

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

it('includes login if not logged in', function () {
    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});

it('includes logout not logged in', function () {
    // Act & Assert
    loginAsUser();
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Log Out')
        ->assertSee(route('logout'));
});

it('includes courses links', function () {
    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.home'))
        ->assertOk()
        ->assertSee([
            route('pages.course-details', $firstCourse),
            route('pages.course-details', $secondCourse),
            route('pages.course-details', $lastCourse),
        ]);
});
