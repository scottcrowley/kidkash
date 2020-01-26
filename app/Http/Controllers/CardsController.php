<?php

namespace App\Http\Controllers;

use App\Card;
use App\Vendor;

class CardsController extends Controller
{
    use CardHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::with('cards')->with('cards.transactions.owner')->orderBy('name')->get()->values();

        return view('cards.index', compact('vendors'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        $card->load('vendor')->load('transactions.owner');
        return view('cards.show', compact('card'));
    }

    /**
     * Display all cards for a given vendor. Used in api call
     *
     * @param string $vendors
     * @return Collection
     */
    public function vendorsCards($vendors)
    {
        return $this->getActiveCardList($vendors);
    }

    /**
     * Display all cards for a given vendor & user. Used in api call
     *
     * @param string $vendors
     * @param int $user
     * @return Collection
     */
    public function userCards($vendors, $user)
    {
        $vendorCards = $this->vendorsCards($vendors);

        return $vendorCards->filter(function ($card) use ($user) {
            return ($card->transactions->where('owner_id', '=', $user)->isNotEmpty());
        })->values();
    }
}
