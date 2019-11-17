<?php

namespace App\Http\Controllers;

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
        $transactions = Transaction::latest()->with('kid')->with('vendor')->get();

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
        $kids = User::where('is_kid', true)->orderBy('name')->get();

        return view('transactions.create', compact('vendors', 'kids'));
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
            'user_id' => ['required', 'exists:users,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'type' => ['required', Rule::in(['add', 'use'])],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $data['amount'] = ($data['type'] == 'add') ? abs($data['amount']) : -$data['amount'];

        Transaction::create($data);

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
        $kids = User::where('is_kid', true)->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'vendors', 'kids'));
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
            'user_id' => ['required', 'exists:users,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'type' => ['required', Rule::in(['add', 'use'])],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $data['amount'] = ($data['type'] == 'add') ? abs($data['amount']) : -$data['amount'];

        $transaction->update($data);

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
}
