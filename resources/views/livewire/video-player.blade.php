<div>
    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen
        allowfullscreen></iframe>
    <h3>{{ $video->title }} ({{ $video->getReadableDuration() }})</h3>
    <p>{{ $video->description }}</p>

    @if ($video->alreadyWatchedByCurrentUser())
        <button wire:click="maskVideoAsNotCompleted">Mask as not completed</button>
    @else
        <button wire:click="maskVideoAsCompleted">Mask as completed</button>
    @endif

    <ul>
        @foreach ($courseVideos as $courseVideo)
            <li>
                @if ($this->isCurrentVideo($courseVideo))
                    {{ $courseVideo->title }}
                @else
                    <a href="{{ route('pages.course-videos', [$courseVideo->course, $courseVideo]) }}">
                        {{ $courseVideo->title }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</div>
