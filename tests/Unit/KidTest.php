<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KidTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_determine_if_it_is_a_kid()
    {
        $user = create('App\User');
        $kid = createStates('App\User', 'kid');

        $this->assertFalse($user->is_kid);
        $this->assertTrue($kid->is_kid);
    }

    /** @test */
    public function it_requires_a_name()
    {
        $this->signIn($user = create('App\User'));
        $kid = makeStates('App\User', 'kid', ['name' => '']);

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_email()
    {
        $this->signIn($user = create('App\User'));
        $kid = makeStates('App\User', 'kid', ['email' => '']);

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_a_password()
    {
        $this->signIn($user = create('App\User'));
        $kid = makeStates('App\User', 'kid', ['password' => '']);

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_password_confirmation()
    {
        $this->signIn($user = create('App\User'));
        $kid = makeStates('App\User', 'kid');

        $kid->password_confirmation = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('password');
    }
}
