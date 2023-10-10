<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('returns a successful response for home page', function () {
    // Act & Assert
    get(route('pages.home'))->assertOk();
});

it('returns a successful response for course details page', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))->assertOk();
});

it('returns a successful response for dashboard page', function () {
    // Arrange
    $user = User::factory()->create();

    $this->actingAs($user);
    // Act & Assert
    get(route('dashboard'))->assertOk();
});

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('dashboard'))->assertRedirect(route('login'));
});

it('list purchased courses', function () {
    // Arrange
    $courseData = [
        [
            'title' => 'Course A',
        ], [
            'title' => 'Course B',
        ]
    ];
    $user = User::factory()
        ->has(Course::factory()->count(2)->state(
            new Sequence(...$courseData)
        ))
        ->create();

    $this->actingAs($user);

    // Act & Assert
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(...$courseData);
});
