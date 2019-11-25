<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_its_related_card()
    {
        $transaction = create('App\Transaction');
        $card = create('App\Card', ['vendor_id' => $transaction->vendor_id]);
        $cardTransaction = create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        $this->assertEquals($card->number, $cardTransaction->card->number);
    }

    /** @test */
    public function it_can_access_details_about_its_related_transaction()
    {
        $transaction = create('App\Transaction');
        $card = create('App\Card', ['vendor_id' => $transaction->vendor_id]);
        $cardTransaction = create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        $this->assertEquals($transaction->description, $cardTransaction->transaction->description);
    }
}
