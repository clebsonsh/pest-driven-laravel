<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\User;
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

it('mask video as completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory()
            ->state(['title' => 'Course Video'])
        )
        ->create();
    $user->courses()->attach($course);

    //Assert
    expect($user->videos)->toHaveCount(0);

    //Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, [
        'video' => $course->videos()->first(),
    ])->call('maskVideoAsCompleted');

    //Assert
    $user->refresh();
    expect($user->videos)
        ->toHaveCount(1)
        ->first()
        ->title->toEqual('Course Video');
});

it('mask video as not completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory()
            ->state(['title' => 'Course Video'])
        )
        ->create();
    $user->courses()->attach($course);
    $user->videos()->attach($course->videos()->first());

    //Assert
    expect($user->videos)->toHaveCount(1);

    //Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, [
        'video' => $course->videos()->first(),
    ])->call('maskVideoAsNotCompleted');

    //Assert
    $user->refresh();
    expect($user->videos)
        ->toHaveCount(0);
});
