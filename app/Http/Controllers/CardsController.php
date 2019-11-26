<?php

namespace App\Http\Controllers;

use App\Card;
use App\Vendor;

class CardsController extends Controller
{
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
