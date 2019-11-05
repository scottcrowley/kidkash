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
    public function a_user_must_be_a_parent_to_view_all_vendors()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('vendors.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_all_vendors()
    {
        $this->signIn();

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
    public function a_user_must_be_a_parent_to_view_create_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('vendors.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_create_page()
    {
        $this->signIn();

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
    public function a_user_must_be_a_parent_to_add_a_new_vendor()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->post(route('vendors.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_add_a_new_vendor()
    {
        $this->signIn();

        $vendor = makeRaw('App\Vendor');

        $this->post(route('vendors.store'), $vendor);

        $this->assertDatabaseHas('vendors', ['name' => $vendor['name']]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_edit_page()
    {
        $vendor = create('App\Vendor');

        $this->get(route('vendors.edit', $vendor->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_must_be_a_parent_to_view_edit_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $vendor = create('App\Vendor');

        $this->get(route('vendors.edit', $vendor->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_edit_page()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.edit', $vendor->id))
            ->assertSee($vendor->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_a_vendor()
    {
        $vendor = create('App\Vendor');

        $this->delete(route('vendors.delete', $vendor->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_must_be_a_parent_to_delete_a_vendor()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $vendor = create('App\Vendor');

        $this->delete(route('vendors.delete', $vendor->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_delete_a_vendor()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->delete(route('vendors.delete', $vendor->id))
            ->assertRedirect(route('vendors.index'));

        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_show_page()
    {
        $vendor = create('App\Vendor');

        $this->get(route('vendors.show', $vendor->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_must_be_a_parent_to_view_show_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $vendor = create('App\Vendor');

        $this->get(route('vendors.show', $vendor->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_show_page()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $this->get(route('vendors.show', $vendor->id))
            ->assertSee($vendor->name);
    }
}
