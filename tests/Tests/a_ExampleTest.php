<?php

use Inertia\Testing\AssertableInertia as Assert;

test('it should returns a successful response', function () {
    $response = $this->get('/');

    $response->assertOk()->assertInertia(fn (Assert $page) => $page->component('Welcome'));
});
