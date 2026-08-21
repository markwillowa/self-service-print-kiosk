<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_registration_when_unregistered(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('registration.index'));
    }
}
