<?php

namespace App\Livewire;

use App\Models\Video;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VideoPlayer extends Component
{
    public $video;

    public $courseVideos;

    public function mount(): void
    {
        $this->courseVideos = $this->video->course->videos;
    }

    public function render(): View
    {
        return view('livewire.video-player');
    }

    public function maskVideoAsCompleted(): void
    {
        auth()->user()->watchedVideos()->attach($this->video);
    }

    public function maskVideoAsNotCompleted(): void
    {
        auth()->user()->watchedVideos()->detach($this->video);
    }

    public function isCurrentVideo(Video $videoToCheck): bool
    {
        return $this->video->id === $videoToCheck->id;
    }
}
