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
    public function an_authenticated_user_may_view_all_kids()
    {
        $this->signIn(create('App\User'));
        $user = create('App\User');

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.index'))
        ->assertSee($kid->name)
        ->assertDontSee($user->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_the_kids_create_page()
    {
        $this->get(route('kids.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_view_the_kids_create_page()
    {
        $this->withoutExceptionHandling();

        $this->signIn($user = create('App\User'));

        $this->get(route('kids.create'))
            ->assertSee('Create a new Kid');
    }

    /** @test */
    public function an_authenticated_user_may_add_a_new_kid()
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

    /** @test */
    public function a_user_must_be_authenticated_to_view_the_kids_edit_page()
    {
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_view_the_kids_edit_page()
    {
        $this->withoutExceptionHandling();

        $this->signIn($user = create('App\User'));
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->id))
            ->assertSee('Edit '.$kid->name);
    }

    /** @test */
    public function an_authenticated_user_may_update_an_existing_kid()
    {
        $this->signIn(create('App\User'));
        $kid = createStatesRaw('App\User', 'kid');
        $kid['name'] = 'Im John Doe';
        $kid['email'] = 'someone@new.com';
        $kid['current_password'] = 'password';
        $kid['password'] = 'newpassword';
        $kid['password_confirmation'] = 'newpassword';

        $this->patch(route('kids.update', $kid['id']), $kid)
            ->assertRedirect(route('kids.index'));

        $this->assertDatabaseHas('users', ['email' => $kid['email']]);

        $dbKid = User::where('email', $kid['email'])->first();

        $this->assertEquals($dbKid->name, $kid['name']);

        $this->assertTrue(Hash::check($kid['password'], $dbKid->password));
    }

    /** @test */
    public function an_authenticated_user_may_delete_a_kid()
    {
        $this->signIn(create('App\User'));
        $kid = createStates('App\User', 'kid');

        $this->delete(route('kids.delete', $kid['id']))
            ->assertRedirect(route('kids.index'));

        $this->assertDatabaseMissing('users', ['id' => $kid->id]);
    }
}
