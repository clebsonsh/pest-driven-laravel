<?php
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows courses overview', function () {
    // Arrange
    $courses = [
        ['title' => 'Course A', 'description' => 'Description A'],
        ['title' => 'Course B', 'description' => 'Description B'],
        ['title' => 'Course C', 'description' => 'Description C']
    ];

    foreach ($courses as $course) {
        Course::factory()->create([...$course, 'released_at' => now()]);
    }

    // Act & Assert
    get(route('home'))->assertSeeText(...$courses);
});

it('shows only released courses', function () {
    // Arrange
    Course::factory()->create(['title' => 'Course A', 'released_at' => now()->subDay()]);
    Course::factory()->create(['title' => 'Course B']);

    // Act & Assert
    get(route('home'))->assertSeeText('Course A')->assertDontSeeText('Course B');
});

it('shows courses by release date', function () {
    // Arrange
    Course::factory()->create(['title' => 'Course A', 'released_at' => now()->subDay()]);
    Course::factory()->create(['title' => 'Course B', 'released_at' => now()]);

    // Act & Assert
    get(route('home'))->assertSeeInOrder(['Course B', 'Course A']);
});
