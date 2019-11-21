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
}
