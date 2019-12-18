<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenicated_to_view_dashboard()
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_is_redirected_to_their_own_dashboard_when_they_log_in_if_they_are_not_an_authorized_parent()
    {
        $this->signIn();

        $this->get(route('home'))
            ->assertSee('Dashboard');
    }

    /** @test */
    public function an_authenticated_authorized_parent_is_redirected_to_users_index_page_when_they_log_in()
    {
        $this->signInParent();

        $this->get(route('home'))
            ->assertRedirect(route('users.index'));
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_the_index_page()
    {
        $this->get(route('users.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_all_users()
    {
        $this->signIn();

        $this->get(route('users.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_all_users()
    {
        $this->signInParent();

        $user = create('App\User');

        $this->get(route('users.index'))
            ->assertSee($user->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_the_create_page()
    {
        $this->get(route('users.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_the_create_page()
    {
        $this->signIn();

        $this->get(route('users.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_the_create_page()
    {
        $this->signInParent();

        $this->get(route('users.create'))
            ->assertStatus(200)
            ->assertSee('Add a new User');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_add_a_new_user()
    {
        $this->post(route('users.store'), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_add_a_new_user()
    {
        $this->signIn();

        $this->post(route('users.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_add_a_new_user()
    {
        $this->signInParent();

        $user = makeRaw('App\User');
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $this->post(route('users.store'), $user)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => $user['email']]);

        $dbUser = User::where('email', $user['email'])->first();

        $this->assertTrue(Hash::check($user['password'], $dbUser->password));
    }

    /** @test */
    public function a_user_must_authenticated_to_view_the_edit_page()
    {
        $user = create('App\User');

        $this->get(route('users.edit', $user->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_not_view_a_different_users_edit_page()
    {
        $user = create('App\User');
        $this->signIn();

        $this->get(route('users.edit', $user->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_the_edit_page()
    {
        $this->signInParent();

        $user = create('App\User');

        $this->get(route('users.edit', $user->slug))
            ->assertSee('Edit '.$user->name)
            ->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_may_view_their_own_edit_page()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('users.edit', $user->slug))
            ->assertSee('Edit Your Profile')
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_update_an_existing_user()
    {
        $user = create('App\User');

        $this->patch(route('users.update', $user->slug), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_not_update_a_different_user()
    {
        $user = create('App\User');

        $this->signIn();

        $this->patch(route('users.update', $user->slug), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_user()
    {
        $this->signInParent();

        $user = createRaw('App\User');
        $user['name'] = 'John Doe';
        $user['email'] = 'someone@new.com';
        $user['current_password'] = 'password';
        $user['password'] = 'newpassword';
        $user['password_confirmation'] = 'newpassword';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => $user['email']]);

        $dbUser = User::find($user['id']);

        $this->assertEquals($dbUser->name, $user['name']);
        $this->assertEquals($dbUser->email, $user['email']);

        $this->assertTrue(Hash::check($user['password'], $dbUser->password));
    }

    /** @test */
    public function an_authenticated_user_may_update_their_own_profile()
    {
        $this->signIn($user = create('App\User'));
        $user = $user->toArray();

        $user['name'] = 'John Doe';
        $user['email'] = 'someone@new.com';
        $user['current_password'] = 'password';
        $user['password'] = 'newpassword';
        $user['password_confirmation'] = 'newpassword';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => $user['email']]);

        $dbUser = User::find($user['id']);

        $this->assertEquals($dbUser->name, $user['name']);
        $this->assertEquals($dbUser->email, $user['email']);

        $this->assertTrue(Hash::check($user['password'], $dbUser->password));
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_an_existing_user()
    {
        $user = create('App\User');

        $this->delete(route('users.update', $user->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_may_not_delete_a_different_user()
    {
        $user = create('App\User');

        $this->signIn(create('App\User'));

        $this->delete(route('users.delete', $user->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_a_user()
    {
        $this->signInParent();

        $user = create('App\User');

        $this->delete(route('users.delete', $user->slug))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function an_authenticated_user_may_not_delete_their_own_account()
    {
        $this->signIn();

        $this->delete(route('users.delete', auth()->user()->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_show_page()
    {
        $user = create('App\User');

        $this->get(route('users.show', $user->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_must_be_an_authorized_parent_to_view_show_page()
    {
        $this->signIn();

        $user = create('App\User');

        $this->get(route('users.show', $user->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_show_page()
    {
        $this->signInParent();

        $user = create('App\User');

        $this->get(route('users.show', $user->slug))
            ->assertSee($user->name);
    }

    /** @test */
    public function an_authenticated_user_may_not_see_sub_nav()
    {
        $this->signIn(create('App\User'));

        $this->get(route('home'))
            ->assertDontSee('Manage Users');
    }

    /** @test */
    public function an_authenticated_authorized_parent_can_see_sub_nav()
    {
        $this->signInParent();

        $this->get(route('users.index'))
            ->assertSee('Manage Users');
    }

    /** @test */
    public function an_authenticated_user_may_not_see_delete_button_on_edit_page()
    {
        $this->signIn();

        $this->get(route('users.edit', auth()->user()->slug))
            ->assertDontSee('delete');
    }

    /** @test */
    public function an_authenticated_authorized_parent_can_see_delete_button_on_edit_page()
    {
        $this->signInParent();
        $user = create('App\User');

        $this->get(route('users.edit', $user->slug))
            ->assertSee('delete');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_retrieve_a_list_of_all_users_through_api()
    {
        $this->get(route('api.users.list'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_retrieve_a_list_of_all_users_through_api()
    {
        $this->signIn();

        $this->json('get', route('api.users.list'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_retrieve_a_list_of_all_users_through_api()
    {
        $this->signInParent();

        $user = create('App\User');

        $response = $this->json('get', route('api.users.list'));
        $users = $response->getData();

        $this->assertCount(2, $users);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_retrieve_a_list_of_users_excluding_a_given_user_through_api()
    {
        $this->signInParent();

        $user = create('App\User');

        $response = $this->json('get', route('api.users.list', $user->id));
        $users = $response->getData();

        $this->assertCount(1, $users);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_retrieve_a_users_vendors_list_through_api()
    {
        $user = create('App\User');
        $this->get(route('api.users.vendors', $user->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_retrieve_a_users_vendors_list_through_api()
    {
        $this->signIn();
        $user = create('App\User');

        $this->json('get', route('api.users.vendors', $user->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_retrieve_a_users_vendors_list_through_api()
    {
        $this->signInParent();

        $user = create('App\User');

        $vendor = create('App\Vendor');

        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id], 2);
        create('App\Transaction', ['owner_id' => $user->id], 2);

        $response = $this->json('get', route('api.users.vendors', $user->id));
        $vendorsList = $response->getData();

        $this->assertCount(3, $vendorsList);
    }
}
