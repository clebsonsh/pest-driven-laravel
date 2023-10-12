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

    // Act & Assert
});

it('show latest purchased course first', function () {
    // Arrange

    // Act & Assert
});

it('includes a link to product videos', function () {
    // Arrange

    // Act & Assert
});
