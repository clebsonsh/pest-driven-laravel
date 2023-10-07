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
        Course::factory()->create($course);
    }

    // Act & Assert
    get(route('home'))->assertSeeText(...$courses);
});

it('shows only released courses', function () {
    // Arrange

    // Act

    // Assert
});

it('shows courses by release date', function () {
    // Arrange

    // Act

    // Assert
});
