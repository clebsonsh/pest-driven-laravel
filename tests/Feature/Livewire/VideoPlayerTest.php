<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Livewire\Livewire;

function createCourseAndVideo(int $videosCount = 1): Course
{
    return Course::factory()
        ->has(Video::factory()->count($videosCount))
        ->create();
}

beforeEach(function () {
    $this->loggedInUser = loginAsUser();
});

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

it('mark video as completed', function () {
    // Arrange
    $course = createCourseAndVideo();
    $this->loggedInUser->purchasedCourses()->attach($course);

    //Assert
    expect($this->loggedInUser->watchedVideos)->toHaveCount(0);

    //Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertMethodWired('maskVideoAsCompleted')
        ->call('maskVideoAsCompleted')
        ->assertMethodNotWired('maskVideoAsCompleted')
        ->assertMethodWired('maskVideoAsNotCompleted');

    //Assert
    $this->loggedInUser->refresh();
    expect($this->loggedInUser->watchedVideos)
        ->toHaveCount(1)
        ->first()
        ->title->toEqual($course->videos()->first()->title);
});

it('mask video as not completed', function () {
    // Arrange
    $course = createCourseAndVideo();
    $this->loggedInUser->purchasedCourses()->attach($course);
    $this->loggedInUser->watchedVideos()->attach($course->videos()->first());

    //Assert
    expect($this->loggedInUser->watchedVideos)->toHaveCount(1);

    //Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertMethodWired('maskVideoAsNotCompleted')
        ->call('maskVideoAsNotCompleted')
        ->assertMethodNotWired('maskVideoAsNotCompleted')
        ->assertMethodWired('maskVideoAsCompleted');

    //Assert
    $this->loggedInUser->refresh();
    expect($this->loggedInUser->watchedVideos)
        ->toHaveCount(0);
});
