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
}
