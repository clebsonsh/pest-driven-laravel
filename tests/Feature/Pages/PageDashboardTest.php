<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('pages.dashboard'))->assertRedirect(route('login'));
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
    loginAsUser($user);

    // Assert
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText(...$courseData);
});

it('does not list other courses', function () {
    // Arrange
    $course = Course::factory()->create();

    // Act
    loginAsUser();

    // Assert
    get(route('pages.dashboard'))
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
    loginAsUser($user);

    // Assert
    get(route('pages.dashboard'))
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
    loginAsUser($user);

    // Assert
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('pages.course-videos', Course::first()));
});
