<?php

namespace Tests\Unit;

use App\Card;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_name_when_adding_new()
    {
        $this->signInParent();

        $vendor = makeRaw('App\Vendor', ['name' => '']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_name_when_updating_existing()
    {
        $this->signInParent();

        $vendor = create('App\Vendor');

        $this->patch(route('vendors.update', $vendor->slug), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name_when_adding_new()
    {
        $this->signInParent();

        create('App\Vendor', ['name' => 'New Vendor']);

        $vendor = makeRaw('App\Vendor', ['name' => 'New Vendor']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name_when_updating_existing()
    {
        $this->signInParent();

        create('App\Vendor', ['name' => 'New Vendor']);

        $vendor = createRaw('App\Vendor');
        $vendor['name'] = 'New Vendor';

        $this->patch(route('vendors.update', $vendor['slug']), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_can_access_all_of_its_transactions()
    {
        $vendor = create('App\Vendor');
        create('App\Transaction', ['vendor_id' => $vendor->id], 2);

        $this->assertCount(2, $vendor->transactions);
    }

    /** @test */
    public function it_can_access_all_owners_with_related_transactions()
    {
        $vendor = create('App\Vendor');
        create('App\Transaction', ['vendor_id' => $vendor->id], 4);

        $this->assertCount(4, $vendor->owners);

        $this->assertInstanceOf('App\User', $vendor->owners[0]);
    }

    /** @test */
    public function it_can_access_all_of_its_related_cards()
    {
        $vendor = create('App\Vendor');
        create('App\Card', ['vendor_id' => $vendor->id], 5);

        $this->assertCount(5, $vendor->cards);
        $this->assertInstanceOf(Card::class, $vendor->cards[0]);
    }

    /** @test */
    public function it_can_access_a_list_of_its_cards_with_balances()
    {
        $vendor = create('App\Vendor');
        create('App\Card', ['vendor_id' => $vendor->id]);
        $cardWithBalance = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        create('App\CardTransaction', ['card_id' => $cardWithBalance->id, 'transaction_id' => $transaction->id]);

        $this->assertCount(1, $vendor->fresh()->cards_list);
        $this->assertEquals($cardWithBalance->number, $vendor->cards_list[0]->number);
    }

    /** @test */
    public function it_can_access_a_list_of_its_cards_with_a_zero_balance()
    {
        $vendor = create('App\Vendor');
        $cardWithBalance = create('App\Card', ['vendor_id' => $vendor->id]);
        $cardWithoutBalance = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction = create('App\Transaction', ['vendor_id' => $vendor->id]);
        create('App\CardTransaction', ['card_id' => $cardWithBalance->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['vendor_id' => $vendor->id, 'amount' => 20]);
        create('App\CardTransaction', ['card_id' => $cardWithoutBalance->id, 'transaction_id' => $transaction->id]);
        $transaction = create('App\Transaction', ['vendor_id' => $vendor->id, 'amount' => -20]);
        create('App\CardTransaction', ['card_id' => $cardWithoutBalance->id, 'transaction_id' => $transaction->id]);

        $this->assertCount(1, $vendor->fresh()->empty_cards_list);
        $this->assertEquals($cardWithoutBalance->number, $vendor->empty_cards_list[0]->number);
    }

    /** @test */
    public function it_can_access_a_list_of_owners_with_associated_transactions()
    {
        $vendor = create('App\Vendor');
        $userWithBalance = create('App\User');
        $userWithoutBalance = create('App\User');
        $userWithNegativeBalance = create('App\User');

        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $userWithBalance->id]);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $userWithoutBalance->id, 'amount' => 20]);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $userWithoutBalance->id, 'amount' => -20]);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $userWithNegativeBalance->id, 'amount' => 10]);
        create('App\Transaction', ['vendor_id' => $vendor->id, 'owner_id' => $userWithNegativeBalance->id, 'amount' => -20]);

        $this->assertCount(2, $vendor->fresh()->owners_list);
    }
}
