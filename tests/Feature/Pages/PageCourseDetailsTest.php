<?php

use App\Models\Course;
use App\Models\Video;

use function Pest\Laravel\get;

it('should not found unreleased course', function () {
    $course = Course::factory()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertNotFound();
});

it('show course details', function () {
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/{$course->image_name}"));
});

it('show course videos count', function () {
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3))
        ->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
});

it('includes paddle checkout button', function () {
    // Arrange
    config()->set('services.paddle.vendor_id', 'vendor-id');
    $course = Course::factory()->released()->create([
        'paddle_product_id' => 'product-id',
    ]);

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSee('<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>', false)
        ->assertSee('vendor: vendor-id', false)
        ->assertSee('<a href="#" class="paddle_button" data-product="product-id">Buy Now</a>', false);
});
