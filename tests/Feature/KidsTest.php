<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KidsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_view_the_index_page()
    {
        $this->get(route('kids.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_view_all_kids()
    {
        $this->signIn(create('App\User'));
        $user = create('App\User');

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.index'))
            ->assertSee($kid->name)
            ->assertDontSee($user->name);
    }

    /** @test */
    public function an_authorized_user_can_add_a_new_kid()
    {
        $this->signIn(create('App\User'));
        $kid = makeStatesRaw('App\User', 'kid');
        $kid['password'] = 'password';
        $kid['password_confirmation'] = 'password';

        $this->post(route('kids.store'), $kid)
            ->assertRedirect(route('kids.index'));

        $this->assertDatabaseHas('users', ['email' => $kid['email']]);

        $dbKid = User::where('email', $kid['email'])->first();

        $this->assertTrue(Hash::check($kid['password'], $dbKid->password));
    }
}
