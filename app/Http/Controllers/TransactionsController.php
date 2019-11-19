<?php

namespace App\Http\Controllers;

use App\Card;
use App\User;
use App\Vendor;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with('owner')->with('vendor')->latest()->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::orderBy('name')->get();
        $owners = User::orderBy('name')->get();

        return view('transactions.create', compact('vendors', 'owners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'owner_id' => ['required', 'exists:users,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'type' => ['required', Rule::in(['add', 'use'])],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $data['amount'] = ($data['type'] == 'add') ? abs($data['amount']) : -$data['amount'];

        $transaction = Transaction::create($data);

        if ($request->has('number')) {
            $this->updateTransactionCard($request, $transaction);
        }

        session()->flash('flash', ['message' => 'Transaction added successfully!', 'level' => 'success']);

        return redirect(route('transactions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $vendors = Vendor::orderBy('name')->get();
        $owners = User::orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'vendors', 'owners'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'owner_id' => ['required', 'exists:users,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'type' => ['required', Rule::in(['add', 'use'])],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $data['amount'] = ($data['type'] == 'add') ? abs($data['amount']) : -$data['amount'];

        $transaction->update($data);

        if ($request->has('number')) {
            $this->updateTransactionCard($request, $transaction);
        }

        session()->flash('flash', ['message' => 'Transaction updated successfully!', 'level' => 'success']);

        return redirect(route('transactions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        session()->flash('flash', ['message' => 'Transaction was successfully deleted from the database!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('transactions.index'));
    }

    /**
     * Updates a card associated with this transaction
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Transaction $transaction
     * @return void
     */
    public function updateTransactionCard($request, $transaction)
    {
        $transactionCard = $transaction->card;

        if ($transactionCard) {
            $transactionCard->transactions()->detach($transaction);
        }

        $card = Card::where([
            ['number', '=', $request->number],
            ['owner_id', '=', $transaction->owner_id],
            ['vendor_id', '=', $transaction->vendor_id],
        ])->first();

        if (! $card) {
            $cardData = $request->validate([
                'owner_id' => ['required'],
                'vendor_id' => ['required'],
                'number' => ['required', 'string', 'unique:cards,number'],
                'pin' => ['nullable', 'string'],
            ]);

            $card = Card::create($cardData);
        }

        $card->transactions()->attach($transaction);
    }
}
