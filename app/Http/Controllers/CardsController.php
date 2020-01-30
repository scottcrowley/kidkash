<?php

namespace App\Http\Controllers;

use App\Card;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Card  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        $card->load('transactions')->load('vendor');
        $allVendors = Vendor::orderBy('name')->get();

        return view('cards.edit', compact('card', 'allVendors'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $data = $request->validate([
            'number' => ['required', 'string', 'max:255', Rule::unique('cards')->ignore($card->number)],
            'pin' => ['nullable', 'string', 'max:255'],
            'expiration' => ['nullable', 'date'],
        ]);

        $data['expiration'] = (isset($data['expiration'])) ?
            $this->normalizeDate($data['expiration']) : null;

        $card->update($data);

        session()->flash('flash', ['message' => $card->number.' was successfully updated!', 'level' => 'success']);

        return redirect(route('cards.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();

        session()->flash('flash', ['message' => $card->number.' was successfully deleted from the database!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('cards.index'));
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
