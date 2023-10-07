<?php

use function Pest\Laravel\get;

it('returns a successful response', function () {
    // Act
    get(route('home'))
        // Assert
        ->assertOk();
});
