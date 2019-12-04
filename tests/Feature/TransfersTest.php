<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransfersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_view_create_page()
    {
        $this->get(route('transfers.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_create_page()
    {
        $this->signIn();

        $this->get(route('transfers.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_create_page()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $this->get(route('transfers.create'))
            ->assertSee('Add a new Transfer Transaction');
    }
}
