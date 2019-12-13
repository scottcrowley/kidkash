<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_the_from_transaction()
    {
        $vendor = create('App\Vendor');
        $fromTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $toTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $transfer = create('App\Transfer', ['from_transaction_id' => $fromTransaction->id, 'to_transaction_id' => $toTransaction->id]);

        $this->assertEquals($fromTransaction->id, $transfer->fromTransaction->id);
    }

    /** @test */
    public function it_can_access_details_about_the_to_transaction()
    {
        $vendor = create('App\Vendor');
        $fromTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $toTransaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        $transfer = create('App\Transfer', ['from_transaction_id' => $fromTransaction->id, 'to_transaction_id' => $toTransaction->id]);

        $this->assertEquals($toTransaction->id, $transfer->toTransaction->id);
    }
}
