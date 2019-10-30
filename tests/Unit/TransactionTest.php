<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_the_related_kid()
    {
        $this->signIn();

        $kid = createStates('App\User', 'kid');
        $transaction = create('App\Transaction', ['kid_id' => $kid->id]);

        $this->assertEquals($kid->name, $transaction->kid->name);
    }

    /** @test */
    public function it_can_access_the_related_vendor()
    {
        $this->signIn();

        $vendor = create('App\Vendor');
        $transaction = create('App\Transaction', ['vendor_id' => $vendor->id]);

        $this->assertEquals($vendor->name, $transaction->vendor->name);
    }
}
