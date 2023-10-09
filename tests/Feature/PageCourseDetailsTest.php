<?php

use App\Models\Course;
use App\Models\Video;
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
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee($course->image);
});

it('show course videos count', function () {
    $course = Course::factory()->create();
    Video::factory()->count(3)->create([
        'course_id' => $course->id,
    ]);

    // Act & Assert
    get(route('course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
});
