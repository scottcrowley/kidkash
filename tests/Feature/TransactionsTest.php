<?php

namespace Tests\Feature;

use App\Card;
use Tests\TestCase;
use App\Transaction;
use App\CardTransaction;
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
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_all_transactions()
    {
        $this->signIn();

        $this->get(route('transactions.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_all_transactions()
    {
        $this->signInParent();

        $transaction = create('App\Transaction');

        $this->get(route('transactions.index'))
            ->assertSee($transaction->owner->name);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_create_page()
    {
        $this->get(route('transactions.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_create_page()
    {
        $this->signIn();

        $this->get(route('transactions.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_create_page()
    {
        $this->signInParent();

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
    public function an_authenticated_user_must_be_an_authorized_parent_to_create_a_new_transaction()
    {
        $this->signIn();

        $this->post(route('transactions.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_create_a_new_transaction_without_a_card()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction');
        $transaction['type'] = 'use';

        $this->post(route('transactions.store'), $transaction)
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'owner_id' => $transaction['owner_id'],
            'vendor_id' => $transaction['vendor_id'],
        ]);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_create_a_new_transaction_with_a_new_card()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction');
        $transaction['type'] = 'use';

        $this->post(route('transactions.store'), $transaction + ['number' => '123456789', 'pin' => '1234']);

        $this->assertDatabaseHas('transactions', [
            'owner_id' => $transaction['owner_id'],
            'vendor_id' => $transaction['vendor_id'],
        ]);

        $this->assertDatabaseHas('cards', [
            'number' => '123456789',
            'vendor_id' => $transaction['vendor_id'],
        ]);

        $transaction = Transaction::first();

        $this->assertDatabaseHas('card_transaction', [
            'card_id' => $transaction->card->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_create_a_new_transaction_with_an_existing_card()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction');
        $transaction['type'] = 'use';

        $card = create('App\Card', ['vendor_id' => $transaction['vendor_id']]);

        $this->post(route('transactions.store'), $transaction + ['number' => $card->number, 'pin' => $card->pin]);

        $this->assertDatabaseHas('transactions', [
            'owner_id' => $transaction['owner_id'],
            'vendor_id' => $transaction['vendor_id'],
        ]);

        $this->assertCount(1, Card::all());

        $transaction = Transaction::first();

        $this->assertDatabaseHas('card_transaction', [
            'card_id' => $transaction->card->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_edit_page()
    {
        $this->get(route('transactions.edit', 1))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_edit_page()
    {
        $this->signIn();

        $transaction = create('App\Transaction');

        $this->get(route('transactions.edit', $transaction->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_edit_page()
    {
        $this->signInParent();

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
    public function an_authenticated_user_must_be_an_authorized_parent_to_update_an_existing_transaction()
    {
        $this->signIn();

        $transaction = create('App\Transaction');

        $this->patch(route('transactions.update', $transaction->id), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_transaction_without_a_card()
    {
        $this->signInParent();

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
    public function an_authenticated_authorized_parent_may_update_an_existing_transaction_with_a_new_card()
    {
        $this->signInParent();

        $transaction = createRaw('App\Transaction');
        $transaction['amount'] = 50;
        $transaction['type'] = 'add';

        $this->patch(route('transactions.update', $transaction['id']), $transaction + ['number' => '123456789', 'pin' => '1234']);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction['id'],
            'amount' => $transaction['amount']
        ]);

        $this->assertDatabaseHas('cards', [
            'number' => '123456789',
            'vendor_id' => $transaction['vendor_id'],
        ]);

        $transaction = Transaction::find($transaction['id']);

        $this->assertDatabaseHas('card_transaction', [
            'card_id' => $transaction->card->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_transaction_with_an_existing_card()
    {
        $this->signInParent();

        $transaction = create('App\Transaction');
        $transaction->amount = 50;
        $transaction->type = 'add';

        $card = create('App\Card', ['vendor_id' => $transaction->vendor_id]);

        $this->patch(route('transactions.update', $transaction->id), $transaction->toArray() + ['number' => $card->number, 'pin' => $card->pin]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => $transaction->amount
        ]);

        $this->assertCount(1, Card::all());

        $transaction = Transaction::find($transaction->id);

        $this->assertDatabaseHas('card_transaction', [
            'card_id' => $transaction->card->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_transaction_with_a_card_already_attached()
    {
        $this->signInParent();

        $transaction = create('App\Transaction');
        $transaction->amount = 50;
        $transaction->type = 'add';

        $originalCard = create('App\Card', ['vendor_id' => $transaction->vendor_id]);
        $originalCard->transactions()->attach($transaction);

        $this->assertCount(1, CardTransaction::all());

        $newCard = create('App\Card', ['vendor_id' => $transaction->vendor_id]);

        $this->patch(route('transactions.update', $transaction->id), $transaction->toArray() + ['number' => $newCard->number, 'pin' => $newCard->pin]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => $transaction->amount
        ]);

        $this->assertCount(2, Card::all());

        $transaction = Transaction::find($transaction->id);

        $this->assertDatabaseHas('card_transaction', [
            'card_id' => $transaction->card->id,
            'transaction_id' => $transaction->id,
        ]);

        $this->assertCount(1, CardTransaction::all());
    }

    /** @test */
    public function an_expiration_date_is_correctly_formatted_when_transaction_is_provided_a_new_card()
    {
        $this->withoutExceptionHandling();

        $this->signInParent();

        $transaction = create('App\Transaction');

        $dates = [
            '' => null,
            '04/2020' => '2020-04-30',
            '04/20' => '2020-04-30',
            '04-05-2020' => '2020-04-05',
            '4-5-2020' => '2020-04-05',
            '04-20' => '2020-04-30',
            '04.05.2020' => '2020-04-05',
            '4.5.2020' => '2020-04-05',
            '04.20' => '2020-04-30',
            '04 05 2020' => '2020-04-05',
            '4 5 2020' => '2020-04-05',
            '04 20' => '2020-04-30',
        ];

        foreach ($dates as $test => $result) {
            $this->json(
                'patch',
                route('transactions.update', $transaction->id),
                $transaction->toArray() + ['number' => '555555555', 'pin' => '1234', 'expiration' => $test]
            )->assertStatus(200);

            $card = Card::first();
            $expiration = $card->expiration ? $card->expiration->format('Y-m-d') : null;
            $this->assertEquals($expiration, $result);

            $card->delete();
        }
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_remove_a_card_from_an_existing_transaction()
    {
        $this->signInParent();

        $transaction = create('App\Transaction');
        $transaction->amount = 50;
        $transaction->type = 'add';

        $card = create('App\Card', ['vendor_id' => $transaction->vendor_id]);
        $card->transactions()->attach($transaction);

        $this->assertCount(1, CardTransaction::all());

        $this->patch(route('transactions.update', $transaction->id), $transaction->toArray() + ['number' => '']);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => $transaction->amount
        ]);

        $this->assertEmpty(CardTransaction::all());
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_an_existing_transaction()
    {
        $transaction = create('App\Transaction');

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_delete_an_existing_transaction()
    {
        $this->signIn();

        $transaction = create('App\Transaction');

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_an_existing_transaction()
    {
        $this->signInParent();

        $transaction = create('App\Transaction');

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_an_existing_transaction_with_an_associated_card()
    {
        $this->signInParent();

        $transaction = create('App\Transaction');
        $card = create('App\Card', ['vendor_id' => $transaction->vendor_id]);
        $card->transactions()->attach($transaction);

        $this->delete(route('transactions.delete', $transaction->id))
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);

        $this->assertEmpty(CardTransaction::all());
    }
    
    /** @test */
    public function the_transaction_index_page_displays_amounts_over_999()
    {
        $this->signInParent();

        $amount = '-10000';

        $transaction = create('App\Transaction',['amount' => $amount]);

        $token = '$' . number_format(abs($transaction->raw_amount), 2);

        $this->get(route('transactions.index'))
            ->assertSee($token);
    }

    /** @test */
    public function transactions_can_be_filtered_by_search_field()
    {
        $this->signInParent();

        $vendor = create('App\Vendor',['name' => 'Chungas']);
        $transaction1 = create('App\Transaction',['vendor_id' => $vendor->id]);
        $transaction2 = create('App\Transaction');

        $this->get(route('transactions.index'))
            ->assertSee($vendor->name)
            ->assertSee($transaction2->vendor->name);

        $this->get(route('transactions.index', ['search' => 'chu']))
            ->assertSee($vendor->name)
            ->assertDontSee($transaction2->vendor->name);
    }
}
