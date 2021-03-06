<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_the_related_owner()
    {
        $user = create('App\User');
        $transaction = create('App\Transaction', ['owner_id' => $user->id]);

        $this->assertEquals($user->name, $transaction->owner->name);
    }

    /** @test */
    public function it_can_access_the_related_vendor()
    {
        $vendor = create('App\Vendor');
        $transaction = create('App\Transaction', ['vendor_id' => $vendor->id]);

        $this->assertEquals($vendor->name, $transaction->vendor->name);
    }

    /** @test */
    public function it_can_access_an_associated_card()
    {
        $card = create('App\Card');
        $transaction = create('App\Transaction', ['vendor_id' => $card->vendor_id]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        $this->assertEquals($card->number, $transaction->card->number);
    }

    /** @test */
    public function it_requires_a_vendor_id_when_adding_a_new()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction', ['vendor_id' => null]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function it_requires_a_valid_vendor_id_when_adding_a_new()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction', ['vendor_id' => 5]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function it_requires_an_owner_id_when_adding_a_new()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction', ['owner_id' => null]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('owner_id');
    }

    /** @test */
    public function it_requires_a_valid_owner_id_when_adding_a_new()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction', ['owner_id' => 5]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('owner_id');
    }

    /** @test */
    public function it_requires_a_type_when_adding_a_new()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction', ['type' => '']);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('type');
    }

    /** @test */
    public function it_requires_an_amount_when_adding_a_new()
    {
        $this->signInParent();

        $transaction = makeRaw('App\Transaction', ['amount' => '']);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function it_can_determine_if_it_is_a_transfer()
    {
        $this->withoutExceptionHandling();
        $vendor = create('App\Vendor');
        $fromTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $toTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);

        $this->assertFalse($fromTransaction->has_transfer);
        $this->assertFalse($toTransaction->has_transfer);

        create('App\Transfer', ['from_transaction_id' => $fromTransaction->id, 'to_transaction_id' => $toTransaction->id]);

        $this->assertTrue($fromTransaction->fresh()->has_transfer);
        $this->assertTrue($toTransaction->fresh()->has_transfer);
    }

    /** @test */
    public function it_can_access_details_about_a_transfer_from()
    {
        $vendor = create('App\Vendor');
        $fromTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $toTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $transfer = create('App\Transfer', ['from_transaction_id' => $fromTransaction->id, 'to_transaction_id' => $toTransaction->id]);

        $this->assertEquals($transfer->id, $fromTransaction->transferFrom->id);
    }

    /** @test */
    public function it_can_access_details_about_a_transfer_to()
    {
        $vendor = create('App\Vendor');
        $fromTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $toTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $transfer = create('App\Transfer', ['from_transaction_id' => $fromTransaction->id, 'to_transaction_id' => $toTransaction->id]);

        $this->assertEquals($transfer->id, $toTransaction->transferTo->id);
    }
}
