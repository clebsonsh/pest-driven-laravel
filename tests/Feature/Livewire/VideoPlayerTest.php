<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Livewire\Livewire;

function createCourseAndVideo(int $videosCount = 1): Course
{
    return Course::factory()
        ->has(Video::factory()->count($videosCount))
        ->create();
}

it('shows details for given video', function () {
    // Arrange
    $course = createCourseAndVideo();

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
    $course = createCourseAndVideo();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeHtml('<iframe src="https://player.vimeo.com/video/' . $video->vimeo_id);
});

it('show list of all course videos', function () {
    // Arrange
    $course = createCourseAndVideo(videosCount: 3);

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSee(
            $course->videos->pluck('title')->toArray()
        )->assertSeeHtml([
            route('pages.course-videos', $course->videos[1]),
            route('pages.course-videos', $course->videos[2]),
        ]);
});

it('does not include route for current vido', function () {
    // Arrange
    $course = createCourseAndVideo(videosCount: 3);

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertDontSeeHtml(route('pages.course-videos', $course->videos[0]));
});

it('mask video as completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = createCourseAndVideo();
    $user->purchasedCourses()->attach($course);

    //Assert
    expect($user->watchedVideos)->toHaveCount(0);

    //Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, [
        'video' => $course->videos()->first(),
    ])->call('maskVideoAsCompleted');

    //Assert
    $user->refresh();
    expect($user->watchedVideos)
        ->toHaveCount(1)
        ->first()
        ->title->toEqual($course->videos()->first()->title);
});

it('mask video as not completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = createCourseAndVideo();
    $user->purchasedCourses()->attach($course);
    $user->watchedVideos()->attach($course->videos()->first());

    //Assert
    expect($user->watchedVideos)->toHaveCount(1);

    //Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, [
        'video' => $course->videos()->first(),
    ])->call('maskVideoAsNotCompleted');

    //Assert
    $user->refresh();
    expect($user->watchedVideos)
        ->toHaveCount(0);
});
