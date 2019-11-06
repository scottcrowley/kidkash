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
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = makeStates('App\User', 'kid', ['name' => '']);

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('name');

        $kid = createStates('App\User', 'kid');

        $kid->name = '';

        $this->patch(route('kids.update', $kid->id), $kid->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_email()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = makeStates('App\User', 'kid', ['email' => '']);

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('email');

        $kid = createStates('App\User', 'kid');

        $kid->email = '';

        $this->patch(route('kids.update', $kid->id), $kid->toArray())
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_a_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = makeStates('App\User', 'kid', ['password' => '']);

        $this->post(route('kids.store'), $kid->toArray())
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_password_confirmation()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = makeStatesRaw('App\User', 'kid');

        $kid['password_confirmation'] = '';

        $this->post(route('kids.store'), $kid)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_valid_current_password_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStatesRaw('App\User', 'kid');
        $kid['current_password'] = 'wrong';
        $kid['password'] = 'newpassword';
        $kid['password_confirmation'] = 'newpassword';

        $this->patch(route('kids.update', $kid['id']), $kid)
            ->assertSessionHasErrors('current_password');
    }

    /** @test */
    public function it_requires_a_new_password_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStatesRaw('App\User', 'kid');
        $kid['current_password'] = 'password';
        $kid['password'] = '';

        $this->patch(route('kids.update', $kid['id']), $kid)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_new_password_confirmation_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStatesRaw('App\User', 'kid');
        $kid['current_password'] = 'password';
        $kid['password'] = 'newpassword';
        $kid['password_confirmation'] = '';

        $this->patch(route('kids.update', $kid['id']), $kid)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_different_new_password_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStatesRaw('App\User', 'kid');
        $kid['current_password'] = 'password';
        $kid['password'] = 'password';
        $kid['password_confirmation'] = 'password';

        $this->patch(route('kids.update', $kid['id']), $kid)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_can_access_all_of_its_transactions()
    {
        $kid = createStates('App\User', 'kid');
        create('App\Transaction', ['kid_id' => $kid->id], 2);

        $this->assertCount(2, $kid->transactions);
    }

    /** @test */
    public function it_can_access_all_vendors_used_in_transactions()
    {
        $kid = createStates('App\User', 'kid');
        create('App\Transaction', ['kid_id' => $kid->id], 4);

        $this->assertCount(4, $kid->vendors);

        $this->assertInstanceOf('App\Vendor', $kid->vendors[0]);
    }
}
