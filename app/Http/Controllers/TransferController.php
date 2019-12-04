<?php

namespace App\Http\Controllers;

use App\Card;
use App\User;
use App\Vendor;
use App\Transaction;

class TransferController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = new Transaction();

        $vendors = Vendor::orderBy('name')->get();
        $owners = User::orderBy('name')->get();
        $cards = Card::with('vendor')->get()->sortBy('vendor.name')->values();

        return view('transfers.create', compact('transaction', 'vendors', 'owners', 'cards'));
    }
}
