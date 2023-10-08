<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('show course details', function () {
    $course = Course::factory()->create([
        'tagline' => 'Learn to code',
        'image' => 'image.jpg',
        'learnings' => [
            'routes',
            'views',
            'commands',
        ],
    ]);

    // Act & Assert
    get(route('course-details', $course))
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee($course->image);
});
