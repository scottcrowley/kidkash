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
        $this->signInParent();

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
        $this->signInParent();

        $cardTransaction = create('App\CardTransaction');

        $this->get(route('cards.show', $cardTransaction->card_id))
            ->assertSee($cardTransaction->card->number);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_create_page()
    {
        $this->get(route('cards.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_create_page()
    {
        $this->signIn();

        $this->get(route('cards.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_create_page()
    {
        $this->signInParent();

        $this->get(route('cards.create'))
            ->assertOk()
            ->assertSee('Add a new Card');
    }

    /** @test */
    public function a_user_must_be_authenticated_to_create_a_new_card()
    {
        $this->post(route('cards.store'), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_create_a_new_card()
    {
        $this->signIn();

        $this->post(route('cards.store'), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_create_a_new_card()
    {
        $this->signInParent();

        $card = makeRaw('App\Card');

        $this->post(route('cards.store'), $card)
            ->assertRedirect(route('cards.index'));

        $this->assertDatabaseHas('cards', [
            'number' => $card['number'],
            'vendor_id' => $card['vendor_id'],
        ]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_view_edit_page()
    {
        $card = create('App\Card');
        $this->get(route('cards.edit', $card->id))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_must_be_an_authorized_parent_to_view_edit_page()
    {
        $this->signIn();
        $card = create('App\Card');

        $this->get(route('cards.edit', $card->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_view_edit_page()
    {
        $this->signInParent();

        $card = create('App\Card');

        $this->get(route('cards.edit', $card->id))
            ->assertOk()
            ->assertSee($card->number);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_update_an_existing_card()
    {
        $card = create('App\Card');

        $this->patch(route('cards.update', $card->id), $card->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_update_an_existing_card()
    {
        $this->signIn();

        $card = create('App\Card');

        $this->patch(route('cards.update', $card->id), $card->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_update_an_existing_card()
    {
        $this->signInParent();

        $card = create('App\Card');
        $card->pin = '555';
        $cardData = $card->toArray();
        $cardData['expiration'] = $card->expiration->format('m/d/Y');

        $this->patch(route('cards.update', $card->id), $cardData)
            ->assertRedirect(route('cards.index'));

        $this->assertDatabaseHas('cards', ['number' => $card->number]);
    }

    /** @test */
    public function a_user_must_be_authenticated_to_delete_an_existing_card()
    {
        $card = create('App\Card');

        $this->patch(route('cards.delete', $card->id), $card->toArray())
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_unauthorized_user_may_not_delete_an_existing_card()
    {
        $this->signIn();

        $card = create('App\Card');

        $this->patch(route('cards.delete', $card->id), $card->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_an_existing_card()
    {
        $this->signInParent();

        $card = create('App\Card');

        $this->delete(route('cards.delete', $card->id))
            ->assertRedirect(route('cards.index'));

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
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
        $this->signInParent();

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
