@guest
    <a href="{{ route('login') }}">Login</a>
@else
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log Out</button>
    </form>
@endguest
@forelse ($courses as $course)
    <a href="{{ route('pages.course-details', $course) }}">
        <h2> {{ $course->title }}</h2>
    </a>
    <p> {{ $course->description }}</p>
@empty
    no courses found
@endforelse
