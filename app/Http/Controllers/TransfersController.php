<?php

namespace App\Http\Controllers;

use App\User;
use App\Vendor;
use App\Transfer;
use App\Transaction;

class TransfersController extends Controller
{
    use CardHelpers;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fromTransaction = new Transaction();
        $toTransaction = new Transaction();
        $transfer = new Transfer();

        $vendors = Vendor::orderBy('name')->get();
        $owners = User::orderBy('name')->get();
        $cards = $this->getActiveCardList();

        return view('transfers.create', compact('fromTransaction', 'toTransaction', 'transfer', 'vendors', 'owners', 'cards'));
    }
}
