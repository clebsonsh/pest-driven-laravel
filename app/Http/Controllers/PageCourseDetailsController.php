<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;

class PageCourseDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Course $course): View
    {
        if (! $course->released_at) {
            abort(404);
        }

        return view('pages.course-details', [
            'course' => $course->loadCount('videos'),
        ]);
    }
}
