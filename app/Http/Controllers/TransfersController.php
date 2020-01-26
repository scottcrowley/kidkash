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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'name' => ['required', 'string', 'max:255', 'unique:vendors'],
        //     'url' => ['nullable', 'string', 'max:255'],
        // ]);

        // Vendor::create($data);

        // session()->flash('flash', ['message' => $data['name'].' added successfully!', 'level' => 'success']);

        // return redirect(route('vendors.index'));
    }
}
