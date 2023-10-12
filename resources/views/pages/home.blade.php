@guest
    <a href="{{ route('login') }}">Login</a>
@else
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log Out</button>
    </form>
@endguest
@forelse ($courses as $course)
    <h2> {{ $course->title }}</h2>
    <p> {{ $course->description }}</p>
@empty
    no courses found
@endforelse
