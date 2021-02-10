<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_determine_if_it_is_an_authorized_parent()
    {
        $this->signIn($user = create('App\User'));

        $this->assertFalse($user->is_authorized_parent);

        config(['kidkash.parents' => [auth()->user()->email]]);
        $this->assertTrue($user->is_authorized_parent);
    }

    /** @test */
    public function it_requires_a_name()
    {
        $this->signInParent();

        $user = make('App\User', ['name' => '']);

        $this->post(route('users.store'), $user->toArray())
            ->assertSessionHasErrors('name');

        $user = create('App\User');

        $user->name = '';

        $this->patch(route('users.update', $user->slug), $user->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_email()
    {
        $this->signInParent();

        $user = make('App\User', ['email' => '']);

        $this->post(route('users.store'), $user->toArray())
            ->assertSessionHasErrors('email');

        $user = create('App\User');

        $user->email = '';

        $this->patch(route('users.update', $user->slug), $user->toArray())
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_a_password()
    {
        $this->signInParent();

        $user = make('App\User', ['password' => '']);

        $this->post(route('users.store'), $user->toArray())
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_password_confirmation()
    {
        $this->signInParent();

        $user = makeRaw('App\User');

        $user['password_confirmation'] = '';

        $this->post(route('users.store'), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_valid_current_password_when_updating_to_a_new_password()
    {
        $this->signInParent();

        $user = createRaw('App\User');
        $user['current_password'] = 'wrong';
        $user['password'] = 'newpassword';
        $user['password_confirmation'] = 'newpassword';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('current_password');
    }

    /** @test */
    public function it_requires_a_new_password_when_updating_to_a_new_password()
    {
        $this->signInParent();

        $user = createRaw('App\User');
        $user['current_password'] = 'password';
        $user['password'] = '';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_new_password_confirmation_when_updating_to_a_new_password()
    {
        $this->signInParent();

        $user = createRaw('App\User');
        $user['current_password'] = 'password';
        $user['password'] = 'newpassword';
        $user['password_confirmation'] = '';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_a_different_new_password_when_updating_to_a_new_password()
    {
        $this->signInParent();

        $user = createRaw('App\User');
        $user['current_password'] = 'password';
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $this->patch(route('users.update', $user['slug']), $user)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_can_access_all_of_its_transactions()
    {
        $user = create('App\User');
        create('App\Transaction', ['owner_id' => $user->id], 2);

        $this->assertCount(2, $user->transactions);
    }

    /** @test */
    public function it_can_access_all_vendors_used_in_transactions()
    {
        $user = create('App\User');
        create('App\Transaction', ['owner_id' => $user->id], 4);

        $this->assertCount(4, $user->vendors);

        $this->assertInstanceOf('App\Vendor', $user->vendors[0]);
    }

    /** @test */
    public function it_can_calculate_all_transaction_totals()
    {
        $user = create('App\User');
        create('App\Transaction', ['owner_id' => $user->id, 'amount' => 10], 4);
        create('App\Transaction', ['owner_id' => $user->id, 'amount' => -5], 2);

        $this->assertEquals(30, $user->transaction_totals);
    }

    /** @test */
    public function it_can_access_a_list_of_all_vendors_with_balances_from_related_transactions()
    {
        $user = create('App\User');
        $zeroBalanceVendor = create('App\Vendor');
        $negativeBalanceVendor = create('App\Vendor');

        create('App\Transaction', ['owner_id' => $user->id], 4);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $zeroBalanceVendor->id, 'amount' => 10]);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $zeroBalanceVendor->id, 'amount' => -10]);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $negativeBalanceVendor->id, 'amount' => 10]);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $negativeBalanceVendor->id, 'amount' => -20]);
        create('App\Transaction');

        $this->assertCount(5, $user->vendors_list);
    }

    /** @test */
    public function it_can_access_the_transaction_totals_for_each_venodor_with_related_transactions()
    {
        $user = create('App\User');
        $zeroBalanceVendor = create('App\Vendor');
        $negativeBalanceVendor = create('App\Vendor');
        $vendor = create('App\Vendor');

        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => 10], 4);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $zeroBalanceVendor->id, 'amount' => 10]);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $zeroBalanceVendor->id, 'amount' => -10]);
        create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $negativeBalanceVendor->id, 'amount' => -10]);

        $this->assertEquals(30, $user->vendors_list->sum('owner_transaction_totals'));
        $this->assertEmpty($user->vendors_list->where('id', $zeroBalanceVendor->id));
    }

    /** @test */
    public function it_can_access_a_list_of_all_cards_with_balances()
    {
        $user = create('App\User');
        $vendor = create('App\Vendor');
        $vendor2 = create('App\Vendor');
        $cardWithBalance = create('App\Card', ['vendor_id' => $vendor->id]);
        $cardWithZeroBalance = create('App\Card', ['vendor_id' => $vendor2->id]);

        $transaction1 = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id]);
        create('App\CardTransaction', ['card_id' => $cardWithBalance->id, 'transaction_id' => $transaction1->id]);
        $transaction2 = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor2->id, 'amount' => 10]);
        create('App\CardTransaction', ['card_id' => $cardWithZeroBalance->id, 'transaction_id' => $transaction2->id]);
        $transaction3 = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor2->id, 'amount' => -10]);
        create('App\CardTransaction', ['card_id' => $cardWithZeroBalance->id, 'transaction_id' => $transaction3->id]);

        $this->assertCount(1, $user->cards_list);
    }

    /** @test */
    public function it_can_access_the_card_balance_for_each_card_with_related_transactions()
    {
        $user = create('App\User');
        $vendor = create('App\Vendor');
        $cardWithBalance = create('App\Card', ['vendor_id' => $vendor->id]);
        $cardWithZeroBalance = create('App\Card', ['vendor_id' => $vendor->id]);

        $transaction1 = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id]);
        create('App\CardTransaction', ['card_id' => $cardWithBalance->id, 'transaction_id' => $transaction1->id]);
        $transaction2 = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => 10]);
        create('App\CardTransaction', ['card_id' => $cardWithZeroBalance->id, 'transaction_id' => $transaction2->id]);
        $transaction3 = create('App\Transaction', ['owner_id' => $user->id, 'vendor_id' => $vendor->id, 'amount' => -10]);
        create('App\CardTransaction', ['card_id' => $cardWithZeroBalance->id, 'transaction_id' => $transaction3->id]);

        $this->assertCount(1, $user->cards_list[$vendor->name]);
        $this->assertEquals($transaction1->amount, $user->cards_list[$vendor->name]->sum('card_balance'));
    }
}
