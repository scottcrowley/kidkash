<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_view_all_vendors()
    {
        $this->get(route('vendors.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_view_all_vendors()
    {
        $this->signIn();

        $this->get(route('vendors.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_all_vendors()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.index'))
            ->assertSee($vendor->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_create_page()
    {
        $this->get(route('vendors.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_view_create_page()
    {
        $this->signIn();

        $this->get(route('vendors.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_create_page()
    {
        $this->signInParent();

        $this->get(route('vendors.create'))
            ->assertSee('Add a new Vendor');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_add_a_new_vendor()
    {
        $this->post(route('vendors.store'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_add_a_new_vendor()
    {
        $this->signIn();

        $this->post(route('vendors.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_add_a_new_vendor()
    {
        $this->signInParent();

        $vendor = makeRaw('App\Vendor');

        $this->post(route('vendors.store'), $vendor);

        $this->assertDatabaseHas('vendors', ['name' => $vendor['name']]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_edit_page()
    {
        $vendor = create('App\Vendor');

        $this->get(route('vendors.edit', $vendor->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_view_edit_page()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.edit', $vendor->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_edit_page()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.edit', $vendor->slug))
            ->assertSee($vendor->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_update_an_existing_vendor()
    {
        $vendor = create('App\Vendor');

        $this->patch(route('vendors.update', $vendor->slug), $vendor->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_update_an_existing_vendor()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->patch(route('vendors.update', $vendor->slug), $vendor->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_vendor()
    {
        $this->signInParent();

        $vendor = createRaw('App\Vendor');
        $vendor['name'] = 'New Vendor Name';

        $this->patch(route('vendors.update', $vendor['slug']), $vendor)
            ->assertRedirect(route('vendors.index'));

        $this->assertDatabaseHas('vendors', ['name' => $vendor['name']]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_a_vendor()
    {
        $vendor = create('App\Vendor');

        $this->delete(route('vendors.delete', $vendor->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_delete_a_vendor()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->delete(route('vendors.delete', $vendor->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_a_vendor()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');

        $this->delete(route('vendors.delete', $vendor->slug))
            ->assertRedirect(route('vendors.index'));

        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_show_page()
    {
        $vendor = create('App\Vendor');

        $this->get(route('vendors.show', $vendor->slug))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_view_show_page()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.show', $vendor->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_show_page()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.show', $vendor->slug))
            ->assertSee($vendor->name);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_display_vendor_details_for_a_given_user()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');
        $user = create('App\User');
        $user2 = create('App\User');

        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user->id, 'amount' => '50']);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user2->id]);
        $this->get(route('vendors.show', [$vendor->slug, 'user' => $user->slug]))
            ->assertSee($user->name)
            ->assertDontSee($user2->name);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_display_vendor_details_for_a_given_user_with_transactions_over_999()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');
        $user = create('App\User');
        $user2 = create('App\User');

        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user->id, 'amount' => '1000']);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user->id, 'amount' => '1000']);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user2->id]);
        $this->get(route('vendors.show', [$vendor->slug, 'user' => $user->slug]))
            ->assertSee($user->name)
            ->assertDontSee($user2->name)
            ->assertSee('$ 2,000.00');
    }

    /** @test */
    public function the_show_page_displays_balances_for_users_with_associated_nonzero_transaction_balances()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');
        $vendor2 = create('App\Vendor');
        $user = create('App\User');
        $user2 = create('App\User');

        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user->id, 'amount' => '2000']);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user->id, 'amount' => '-500']);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $user2->id, 'amount' => '100']);
        create('App\Transaction', ['vendor_id' => $vendor2->id, 'owner_id' => $user2->id]);
        $this->get(route('vendors.show', [$vendor->slug]))
            ->assertSee($user->name)
            ->assertSee($user2->name)
            ->assertSee('$ 1,500.00')
            ->assertSee('$ 100.00');
    }
}
