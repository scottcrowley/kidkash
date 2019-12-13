<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_view_all_cards()
    {
        $this->get(route('cards.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_all_cards()
    {
        $this->signIn();

        $this->get(route('cards.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_all_cards()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $cardTransaction = create('App\CardTransaction');

        $this->get(route('cards.index'))
            ->assertSee($cardTransaction->card->number);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_show_page()
    {
        $card = create('App\Card');
        $this->get(route('cards.show', $card->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_show_page()
    {
        $this->signIn();
        $card = create('App\Card');

        $this->get(route('cards.show', $card->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_show_page()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $cardTransaction = create('App\CardTransaction');

        $this->get(route('cards.show', $cardTransaction->card_id))
            ->assertSee($cardTransaction->card->number);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_retrieve_a_list_of_all_cards_for_a_vendor_through_api()
    {
        $vendor = create('App\Vendor');
        $this->get(route('api.cards.vendor', $vendor->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_retrieve_a_list_of_all_cards_for_a_vendor_through_api()
    {
        $this->signIn();
        $vendor = create('App\Vendor');

        $this->json('get', route('api.cards.vendor', $vendor->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_retrieve_a_list_of_all_cards_with_balances_for_a_vendor_through_api()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $vendor = create('App\Vendor');

        $card = create('App\Card', ['vendor_id' => $vendor->id]);
        $transaction = create('App\Transaction', ['owner_id' => auth()->user()->id, 'vendor_id' => $vendor->id]);
        create('App\CardTransaction', ['card_id' => $card->id, 'transaction_id' => $transaction->id]);

        create('App\Card', ['vendor_id' => $vendor->id]);
        create('App\Card');

        $response = $this->json('get', route('api.cards.vendor', $vendor->id));
        $cards = $response->getData();

        $this->assertCount(1, $cards);
        $this->assertEquals($vendor->id, $cards[0]->vendor_id);
    }
}
