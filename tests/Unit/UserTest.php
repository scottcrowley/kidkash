<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_determine_if_it_is_an_authorized_parent()
    {
        $this->signIn($user = create('App\User'));

        $this->assertFalse($user->is_authorized_parent);

        config(['kidkash.parents' => [auth()->user()->email]]);
        $this->assertTrue($user->is_authorized_parent);
    }

    /** @test */
    public function it_requires_a_name()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = make('App\User', ['name' => '']);

        $this->post(route('users.store'), $user->toArray())
            ->assertSessionHasErrors('name');

        $user = create('App\User');

        $user->name = '';

        $this->patch(route('users.update', $user->slug), $user->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_email()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = make('App\User', ['email' => '']);

        $this->post(route('users.store'), $user->toArray())
            ->assertSessionHasErrors('email');

        $user = create('App\User');

        $user->email = '';

        $this->patch(route('users.update', $user->slug), $user->toArray())
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_a_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = make('App\User', ['password' => '']);

        $this->post(route('users.store'), $user->toArray())
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_password_confirmation()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = makeRaw('App\User');

        $user['password_confirmation'] = '';

        $this->post(route('users.store'), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_valid_current_password_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = createRaw('App\User');
        $user['current_password'] = 'wrong';
        $user['password'] = 'newpassword';
        $user['password_confirmation'] = 'newpassword';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('current_password');
    }

    /** @test */
    public function it_requires_a_new_password_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = createRaw('App\User');
        $user['current_password'] = 'password';
        $user['password'] = '';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_new_password_confirmation_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = createRaw('App\User');
        $user['current_password'] = 'password';
        $user['password'] = 'newpassword';
        $user['password_confirmation'] = '';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_different_new_password_when_updating_to_a_new_password()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = createRaw('App\User');
        $user['current_password'] = 'password';
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_can_access_all_of_its_transactions()
    {
        $user = create('App\User');
        create('App\Transaction', ['owner_id' => $user->id], 2);

        $this->assertCount(2, $user->transactions);
    }

    /** @test */
    public function it_can_access_all_vendors_used_in_transactions()
    {
        $user = create('App\User');
        create('App\Transaction', ['owner_id' => $user->id], 4);

        $this->assertCount(4, $user->vendors);

        $this->assertInstanceOf('App\Vendor', $user->vendors[0]);
    }
}
