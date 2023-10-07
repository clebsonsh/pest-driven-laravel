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

    get(route('home'))->assertSeeText(...$courses);
});

it('shows only released courses', function () {
    // Arrange
    $releasedCourse = Course::factory()
        ->released()
        ->create();
    $notReleasedCourse = Course::factory()->create();

    // Act & Assert
    get(route('home'))
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
    get(route('home'))
        ->assertSeeInOrder([
            $latestReleasedCourse->title,
            $releasedCourse->title
        ]);
});