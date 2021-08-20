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
    use CardHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = new Transaction;

        $transaction->owner_id = (request()->has('user')) ?
            User::whereSlug(request('user'))->first()->id : 0;
        $transaction->vendor_id = (request()->has('vendor')) ?
            Vendor::whereSlug(request('vendor'))->first()->id : 0;
        
        $preselectedCard = request()->has('card') ?
            Card::findOrFail(request('card'))->id : 0;

        $transaction->type = ($preselectedCard) ? 'use' : 0;
        $vendors = Vendor::orderBy('name')->get();
        $owners = User::orderBy('name')->get();
        $cards = $this->getActiveCardList();
        
        return view('transactions.create', compact('transaction', 'vendors', 'owners', 'cards', 'preselectedCard'));
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

        $this->updateTransactionCard($request, $transaction);

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
        $transaction->load('card')->append('number')->append('pin')->append('expiration');
        $vendors = Vendor::orderBy('name')->get();
        $owners = User::orderBy('name')->get();
        $cards = $this->getActiveCardList($transaction->vendor_id);

        if ($transaction->card && ! $cards->where('id', $transaction->card->id)->count()) {
            $cards = $cards->push($transaction->card->load('vendor')->append('balance'));
        }

        return view('transactions.edit', compact('transaction', 'vendors', 'owners', 'cards'));
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

        $this->updateTransactionCard($request, $transaction);

        session()->flash('flash', ['message' => 'Transaction updated successfully!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response([], 200);
        }

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

        if ($request->number != '') {
            $card = Card::where([
                ['number', '=', $request->number],
                ['vendor_id', '=', $transaction->vendor_id],
            ])->first();

            if (! $card) {
                $cardData = $request->validate([
                    'vendor_id' => ['required', 'exists:vendors,id'],
                    'number' => ['required', 'string', 'unique:cards,number'],
                    'pin' => ['nullable', 'string'],
                    'expiration' => ['nullable', 'string'],
                ]);

                $cardData['expiration'] = (isset($cardData['expiration'])) ?
                    $this->normalizeDate($cardData['expiration']) : null;

                $card = Card::create($cardData);
            }

            $card->transactions()->attach($transaction);
        }
    }
}
