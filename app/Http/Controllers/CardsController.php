<?php

namespace App\Http\Controllers;

use App\Card;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with('transactions')->with('vendor')->paginate(20);

        return view('cards.index', compact('cards'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return view('cards.show', compact('card'));
    }

    /**
     * Display all cards for a given vendor. Used in api call
     *
     * @param int $vendor
     * @return Collection
     */
    public function vendorCards($vendor)
    {
        $where = [];
        if ($vendor) {
            $where[] = ['vendor_id', '=', $vendor];
        }

        $cards = Card::where($where)->with('vendor')->get();

        return $cards;
    }
}
