<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        return view('pages.dashboard', [
            'purchasedCourses' => auth()->user()->purchasedCourses,
        ]);
    }
}
