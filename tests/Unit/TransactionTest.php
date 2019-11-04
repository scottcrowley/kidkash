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

    /** @test */
    public function it_requires_a_vendor_id_when_adding_a_new()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction', ['vendor_id' => null]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function it_requires_a_valid_vendor_id_when_adding_a_new()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction', ['vendor_id' => 5]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('vendor_id');
    }

    /** @test */
    public function it_requires_a_kid_id_when_adding_a_new()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction', ['kid_id' => null]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('kid_id');
    }

    /** @test */
    public function it_requires_a_valid_kid_id_when_adding_a_new()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction', ['kid_id' => 5]);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('kid_id');
    }

    /** @test */
    public function it_requires_a_type_when_adding_a_new()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction', ['type' => '']);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('type');
    }

    /** @test */
    public function it_requires_an_amount_when_adding_a_new()
    {
        $this->signIn();

        $transaction = makeRaw('App\Transaction', ['amount' => '']);

        $this->post(route('transactions.store'), $transaction)
            ->assertSessionHasErrors('amount');
    }
}
