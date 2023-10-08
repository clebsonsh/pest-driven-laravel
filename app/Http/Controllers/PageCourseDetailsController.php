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
        return view('course-details', [
            'course' => $course,
        ]);
    }
}
