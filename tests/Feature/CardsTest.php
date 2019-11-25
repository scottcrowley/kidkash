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
}
