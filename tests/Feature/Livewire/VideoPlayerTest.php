<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;

it('shows details for given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeText([
            $video->title,
            $video->description,
            "({$video->duration_in_min}min)",
        ]);
});

it('shows given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeHtml('<iframe src="https://player.vimeo.com/video/'.$video->vimeo_id);
});

it('show list of all course videos', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()
            ->count(3)
            ->state(new Sequence(
                ['title' => 'First Video'],
                ['title' => 'Second Video'],
                ['title' => 'Last Video'],
            ))
        )
        ->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSee([
            'First Video',
            'Second Video',
            'Last Video',
        ])->assertSeeHtml([
            route('pages.course-videos', Video::whereTitle('First Video')->first()),
            route('pages.course-videos', Video::whereTitle('Second Video')->first()),
            route('pages.course-videos', Video::whereTitle('Last Video')->first()),
        ]);
});
