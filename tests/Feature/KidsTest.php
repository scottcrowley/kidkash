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
        $parent = create('App\User');

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.index'))
            ->assertSee($kid->name)
            ->assertDontSee($parent->name);
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_view_the_kids_create_page()
    {
        $this->get(route('kids.create'))
            ->assertRedirect(route('login'));

        $kid = createStates('App\User', 'kid');

        $this->actingAs($kid);

        $this->get(route('kids.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_the_kids_create_page()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('kids.create'))
            ->assertStatus(200)
            ->assertSee('Create a new Kid');
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_add_a_new_kid()
    {
        $this->get(route('kids.store'))
            ->assertRedirect(route('login'));

        $this->signIn($user = createStates('App\User', 'kid'));

        $kid = makeStatesRaw('App\User', 'kid');

        $this->post(route('kids.store'), $kid)
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_add_a_new_kid()
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
    public function a_user_must_an_authenticated_parent_to_view_the_kids_edit_page()
    {
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_kid_to_view_the_kids_edit_page()
    {
        $kid = createStates('App\User', 'kid');
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('kids.edit', $kid->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_the_kids_edit_page()
    {
        $this->signIn(create('App\User'));
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->id))
            ->assertSee('Edit '.$kid->name)
            ->assertStatus(200);

        $this->signIn($kid);

        $this->get(route('kids.edit', $kid->id))
            ->assertSee('Edit Your Profile')
            ->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_and_authorized_kid_may_view_their_own_edit_page()
    {
        $this->signIn($kid = createStates('App\User', 'kid'));

        $this->get(route('kids.edit', $kid->id))
            ->assertSee('Edit Your Profile')
            ->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_update_an_existing_kid()
    {
        $kid = createStates('App\User', 'kid');

        $this->patch(route('kids.update', $kid->id), $kid->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_kid_to_update_an_existing_kid()
    {
        $kid = createStates('App\User', 'kid');

        $this->signIn(createStates('App\User', 'kid'));

        $this->patch(route('kids.update', $kid->id), $kid->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_update_an_existing_kid()
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

        $dbKid = User::find($kid['id']);

        $this->assertEquals($dbKid->name, $kid['name']);
        $this->assertEquals($dbKid->email, $kid['email']);

        $this->assertTrue(Hash::check($kid['password'], $dbKid->password));
    }

    /** @test */
    public function an_authenticated_and_authorized_kid_may_update_their_own_profile()
    {
        $this->signIn($kid = createStates('App\User', 'kid'));
        $kid = $kid->toArray();

        $kid['name'] = 'Im John Doe';
        $kid['email'] = 'someone@new.com';
        $kid['current_password'] = 'password';
        $kid['password'] = 'newpassword';
        $kid['password_confirmation'] = 'newpassword';

        $this->patch(route('kids.update', $kid['id']), $kid)
            ->assertRedirect(route('kids.index'));

        $this->assertDatabaseHas('users', ['email' => $kid['email']]);

        $dbKid = User::find($kid['id']);

        $this->assertEquals($dbKid->name, $kid['name']);
        $this->assertEquals($dbKid->email, $kid['email']);

        $this->assertTrue(Hash::check($kid['password'], $dbKid->password));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_delete_a_child()
    {
        $kid = createStates('App\User', 'kid');

        $this->delete(route('kids.delete', $kid['id']))
            ->assertRedirect(route('login'));

        $this->signIn(createStates('App\User', 'kid'));

        $this->delete(route('kids.delete', $kid['id']))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_delete_a_kid()
    {
        $this->signIn(create('App\User'));
        $kid = createStates('App\User', 'kid');

        $this->delete(route('kids.delete', $kid['id']))
            ->assertRedirect(route('kids.index'));

        $this->assertDatabaseMissing('users', ['id' => $kid->id]);
    }
}
