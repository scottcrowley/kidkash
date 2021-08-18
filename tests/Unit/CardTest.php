<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_its_vendor()
    {
        $vendor = create('App\Vendor');
        $card = create('App\Card', ['vendor_id' => $vendor->id]);

        $this->assertEquals($vendor->name, $card->vendor->name);
    }

    /** @test */
    public function it_can_access_details_about_all_its_transactions()
    {
        $transaction = create('App\Transaction');
        $card = create('App\Card', ['vendor_id' => $transaction->vendor_id]);

        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        $this->assertCount(1, $card->transactions);

        $this->assertInstanceOf(Transaction::class, $card->transactions[0]);
    }

    /** @test */
    public function it_requires_a_vendor_id_when_adding_a_new()
    {
        $this->signInParent();

        $card = makeRaw('App\Card', ['vendor_id' => null]);

        $this->post(route('cards.store'), $card)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function it_requires_a_valid_vendor_id_when_adding_a_new()
    {
        $this->signInParent();

        $card = makeRaw('App\Card');
        $card['vendor_id'] = 99;

        $this->post(route('cards.store'), $card)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function it_requires_a_number_when_adding_a_new()
    {
        $this->signInParent();

        $card = makeRaw('App\Card', ['number' => '']);

        $this->post(route('cards.store'), $card)
            ->assertSessionHasErrors('number');
    }

    /** @test */
    public function it_requires_a_unique_number_when_adding_a_new()
    {
        $this->signInParent();

        $existingCard = create('App\Card');
        $card = makeRaw('App\Card');
        $card['number'] = $existingCard->number;

        $this->post(route('cards.store'), $card)
            ->assertSessionHasErrors('number');
    }
    
    /** @test */
    public function it_can_access_the_balance_for_all_related_transactions()
    {
        $user = create('App\User');
        $vendor = create('App\Vendor');
        $card = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => 500]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => -50]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        $this->assertEquals(450, $card->balance);
    }

    /** @test */
    public function it_can_access_the_balance_for_all_related_transactions_over_999()
    {
        $user = create('App\User');
        $vendor = create('App\Vendor');
        $card = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => 2000]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => -500]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        $this->assertEquals(1500, $card->balance);
    }

    /** @test */
    public function it_can_access_the_balance_for_each_owner()
    {
        $user1 = create('App\User');
        $user2 = create('App\User');
        $vendor = create('App\Vendor');
        $card = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction = create('App\Transaction', ['owner_id' => $user1->id, 'vendor_id' => $vendor->id, 'amount' => 500]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['owner_id' => $user1->id, 'vendor_id' => $vendor->id, 'amount' => 400]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['owner_id' => $user2->id, 'vendor_id' => $vendor->id, 'amount' => 100]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        
        $this->assertEquals(1000, $card->balance);
        $this->assertEquals(900, $card->owners[0]->card_transaction_totals);
        $this->assertEquals(100, $card->owners[1]->card_transaction_totals);
    }

    /** @test */
    public function it_can_access_the_balance_for_each_owner_with_transactions_over_999()
    {
        $user1 = create('App\User');
        $user2 = create('App\User');
        $vendor = create('App\Vendor');
        $card = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction = create('App\Transaction', ['owner_id' => $user1->id, 'vendor_id' => $vendor->id, 'amount' => 1500]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['owner_id' => $user1->id, 'vendor_id' => $vendor->id, 'amount' => 500]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['owner_id' => $user2->id, 'vendor_id' => $vendor->id, 'amount' => 1000]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);
        
        $this->assertEquals(3000, $card->balance);
        $this->assertEquals(2000, $card->owners[0]->card_transaction_totals);
        $this->assertEquals(1000, $card->owners[1]->card_transaction_totals);
    }
}
