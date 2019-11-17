<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdultsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_adult_is_redirected_to_their_own_dashboard_when_they_log_in()
    {
        $this->signIn();

        $this->get(route('home'))
            ->assertSee('Dashboard');
    }
}
