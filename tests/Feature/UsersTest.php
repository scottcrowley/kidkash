<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_new_kid_is_added_when_using_the_registration_form_and_email_is_not_in_config()
    {
        $user = makeRaw('App\User');
        $user['password_confirmation'] = $user['password'];

        $this->post(route('register'), $user);

        $this->assertTrue(auth()->user()->is_kid);
    }

    /** @test */
    public function a_new_parent_is_added_when_using_the_registration_form_and_email_is_in_config()
    {
        config(['kidkash.parents' => ['john@example.com']]);

        $user = makeRaw('App\User', ['email' => 'john@example.com']);
        $user['password_confirmation'] = $user['password'];

        $this->post(route('register'), $user);

        $this->assertFalse(auth()->user()->is_kid);
    }
}
