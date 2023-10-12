<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('dashboard'))->assertRedirect(route('login'));
});

it('list purchased courses', function () {
    // Arrange
    $courseData = [
        ['title' => 'Course A'],
        ['title' => 'Course B'],
    ];
    $user = User::factory()
        ->has(
            Course::factory()
                ->count(2)
                ->state(new Sequence(...$courseData)),
        )
        ->create();

    // Act
    $this->actingAs($user);

    // Assert
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(...$courseData);
});

it('does not list other courses', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()->create();

    // Act
    $this->actingAs($user);

    // Assert
    get(route('dashboard'))
        ->assertOk()
        ->assertDontSeeText($course->title);
});

it('show latest purchased course first', function () {
    // Arrange
    $user = User::factory()->create();
    $firstPurchasedCourse = Course::factory()->create();
    $lastPurchasedCourse = Course::factory()->create();

    $user->courses()->attach($firstPurchasedCourse, ['created_at' => now()->subDay()]);
    $user->courses()->attach($lastPurchasedCourse, ['created_at' => now()]);

    // Act
    $this->actingAs($user);

    // Assert
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([
            $lastPurchasedCourse->title,
            $firstPurchasedCourse->title,
        ]);
});

it('includes a link to courses videos', function () {
    // Arrange
    $user = User::factory()
        ->has(Course::factory())
        ->create();

    // Act
    $this->actingAs($user);

    // Assert
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('pages.course-videos', Course::first()));
});
