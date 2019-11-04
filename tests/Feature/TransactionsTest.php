<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_view_all_transactions()
    {
        $this->get(route('transactions.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_view_all_transactions()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('transactions.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_all_transactions()
    {
        $this->signIn();

        $transaction = create('App\Transaction');

        $this->get(route('transactions.index'))
            ->assertSee($transaction->kid->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_create_page()
    {
        $this->get(route('transactions.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_view_create_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->get(route('transactions.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_create_page()
    {
        $this->signIn();

        $this->get(route('transactions.create'))
            ->assertSee('Add a new Transaction');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_create_a_new_transaction()
    {
        $this->post(route('transactions.store'), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_create_a_new_transaction()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $this->post(route('transactions.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_create_a_new_transaction()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction');
        $transaction['type'] = 'use';

        $this->post(route('transactions.store'), $transaction)
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'kid_id' => $transaction['kid_id'],
            'vendor_id' => $transaction['vendor_id'],
        ]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_edit_page()
    {
        $this->get(route('transactions.edit', 1))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_view_edit_page()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $transaction = create('App\Transaction');

        $this->get(route('transactions.edit', $transaction->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_view_edit_page()
    {
        $this->signIn();

        $transaction = create('App\Transaction');

        $this->get(route('transactions.edit', $transaction->id))
            ->assertSee('Edit a Transaction');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_update_an_existing_transaction()
    {
        $transaction = create('App\Transaction');

        $this->patch(route('transactions.update', $transaction->id), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_update_an_existing_transaction()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $transaction = create('App\Transaction');

        $this->patch(route('transactions.update', $transaction->id), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_update_an_existing_transaction()
    {
        $this->signIn();

        $transaction = createRaw('App\Transaction');

        $transaction['amount'] = 50;
        $transaction['type'] = 'add';

        $this->patch(route('transactions.update', $transaction['id']), $transaction)
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction['id'],
            'amount' => $transaction['amount']
        ]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_an_existing_transaction()
    {
        $transaction = create('App\Transaction');

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_a_parent_to_delete_an_existing_transaction()
    {
        $this->signIn(createStates('App\User', 'kid'));

        $transaction = create('App\Transaction');

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_delete_an_existing_transaction()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $transaction = create('App\Transaction');

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
