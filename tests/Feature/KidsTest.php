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
    public function an_authenticated_user_must_be_a_parent_to_view_all_kids()
    {
        $this->signIn(createStates('App\User', 'kid'));

        createStates('App\User', 'kid');

        $this->get(route('kids.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_all_kids()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $anotherParent = create('App\User');

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.index'))
            ->assertSee($kid->name)
            ->assertDontSee($anotherParent->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_the_create_page()
    {
        $this->get(route('kids.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_view_the_create_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('kids.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_the_create_page()
    {
        $this->signIn($user = create('App\User'));
        config(['kidkash.parents' => [$user->email]]);

        $this->get(route('kids.create'))
            ->assertStatus(200)
            ->assertSee('Add a new Kid');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_add_a_new_kid()
    {
        $this->post(route('kids.store'), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_add_a_new_kid()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->post(route('kids.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_add_a_new_kid()
    {
        $this->signIn(create('App\User'));
        config(['kidkash.parents' => [auth()->user()->email]]);

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
    public function a_user_must_an_authenticated_authorized_parent_to_view_the_edit_page()
    {
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_kid_to_view_the_edit_page()
    {
        $kid = createStates('App\User', 'kid');
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('kids.edit', $kid->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_the_edit_page()
    {
        $this->signIn(create('App\User'));
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->slug))
            ->assertSee('Edit '.$kid->name)
            ->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_and_authorized_kid_may_view_their_own_edit_page()
    {
        $this->signIn($kid = createStates('App\User', 'kid'));

        $this->get(route('kids.edit', $kid->slug))
            ->assertSee('Edit Your Profile')
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_update_an_existing_kid()
    {
        $kid = createStates('App\User', 'kid');

        $this->patch(route('kids.update', $kid->slug), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_kid_to_update_an_existing_kid()
    {
        $kid = createStates('App\User', 'kid');

        $this->signIn(createStates('App\User', 'kid'));

        $this->patch(route('kids.update', $kid->slug), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_kid()
    {
        $this->signIn(create('App\User'));
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStatesRaw('App\User', 'kid');
        $kid['name'] = 'Im John Doe';
        $kid['email'] = 'someone@new.com';
        $kid['current_password'] = 'password';
        $kid['password'] = 'newpassword';
        $kid['password_confirmation'] = 'newpassword';

        $this->patch(route('kids.update', $kid['slug']), $kid)
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

        $this->patch(route('kids.update', $kid['slug']), $kid)
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => $kid['email']]);

        $dbKid = User::find($kid['id']);

        $this->assertEquals($dbKid->name, $kid['name']);
        $this->assertEquals($dbKid->email, $kid['email']);

        $this->assertTrue(Hash::check($kid['password'], $dbKid->password));
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_an_existing_kid()
    {
        $kid = createStates('App\User', 'kid');

        $this->delete(route('kids.update', $kid->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_delete_a_child()
    {
        $kid = createStates('App\User', 'kid');

        $this->signIn(createStates('App\User', 'kid'));

        $this->delete(route('kids.delete', $kid->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_a_kid()
    {
        $this->signIn(create('App\User'));
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStates('App\User', 'kid');

        $this->delete(route('kids.delete', $kid->slug))
            ->assertRedirect(route('kids.index'));

        $this->assertDatabaseMissing('users', ['id' => $kid->id]);
    }

    /** @test */
    public function an_authenticated_kid_may_not_delete_their_own_account()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->delete(route('kids.delete', auth()->user()->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_show_page()
    {
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.show', $kid->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_must_be_a_parent_to_view_show_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.show', $kid->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_show_page()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.show', $kid->slug))
            ->assertSee($kid->name);
    }

    /** @test */
    public function an_authenticated_kid_may_not_see_sub_nav()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('home'))
            ->assertDontSee('Manage Kids');
    }

    /** @test */
    public function an_authenticated_authorized_parent_can_see_sub_nav()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $this->get(route('kids.index'))
            ->assertSee('Manage Kids');
    }

    /** @test */
    public function an_authenticated_kid_may_not_see_delete_button_on_edit_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('kids.edit', auth()->user()->slug))
            ->assertDontSee('delete');
    }

    /** @test */
    public function an_authenticated_authorized_parent_can_see_delete_button_on_edit_page()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);
        $kid = createStates('App\User', 'kid');

        $this->get(route('kids.edit', $kid->slug))
            ->assertSee('delete');
    }

    /** @test */
    public function an_authenticated_kid_is_redirected_to_their_own_dashboard_when_they_log_in()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('home'))
            ->assertSee('Dashboard');
    }

    /** @test */
    public function an_authenticated_authorized_parent_is_redirected_to_kids_index_page_when_they_log_in()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $this->get(route('home'))
            ->assertRedirect(route('kids.index'));
    }
}
