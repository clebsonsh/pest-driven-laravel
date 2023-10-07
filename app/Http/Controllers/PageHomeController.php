<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PageHomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        return view('home', [
            'courses' => Course::query()
                ->released()
                ->latest('released_at')
                ->get(),
        ]);
    }
}
