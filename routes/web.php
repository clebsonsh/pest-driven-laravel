<?php

use App\Http\Controllers\PageCourseDetailsController;
use App\Http\Controllers\PageDashboardController;
use App\Http\Controllers\PageHomeController;
use App\Http\Controllers\PageVideosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::name('pages.')->group(function () {
    Route::get('/', PageHomeController::class)->name('home');

    Route::get('/courses/{course:slug}', PageCourseDetailsController::class)->name('course-details');

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', PageDashboardController::class)->name('dashboard');
        Route::get('/videos/{course:slug}/{video:slug?}', PageVideosController::class)->name('course-videos');
    });
});
