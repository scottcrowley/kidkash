<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransfersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_view_create_page()
    {
        $this->get(route('transfers.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_create_page()
    {
        $this->signIn();

        $this->get(route('transfers.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_create_page()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $this->get(route('transfers.create'))
            ->assertSee('Add a new Transfer Transaction');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_create_a_new_transfer()
    {
        $this->post(route('transfers.store'), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_create_a_new_transfer()
    {
        $this->signIn();

        $this->post(route('transfers.store'), [])
            ->assertStatus(403);
    }

    // /** @test */
    // public function an_authenticated_authorized_parent_may_create_a_new_transfer_without_a_card()
    // {
    //     $this->signIn($user = create('App\User'));
    //     config(['kidkash.parents' => [$user->email]]);

    //     $userTo = create('App\User');
    //     $vendorFrom = create('App\Vendor');
    //     $vendorTo = create('App\Vendor');

    //     $transfer = [
    //     ];

    //     // $transaction = makeRaw('App\Transaction');
    //     // $transaction['type'] = 'use';

    //     // $this->post(route('transactions.store'), $transaction)
    //     //     ->assertRedirect(route('transactions.index'));

    //     // $this->assertDatabaseHas('transactions', [
    //     //     'owner_id' => $transaction['owner_id'],
    //     //     'vendor_id' => $transaction['vendor_id'],
    //     // ]);
    // }
}
