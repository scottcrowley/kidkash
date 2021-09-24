<?php
namespace App\Http\Controllers;

use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendors.create');
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
            'name' => ['required', 'string', 'max:255', 'unique:vendors'],
            'url' => ['nullable', 'string', 'max:255'],
        ]);

        Vendor::create($data);

        session()->flash('flash', ['message' => $data['name'] . ' added successfully!', 'level' => 'success']);

        return redirect(route('vendors.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        $user = (request()->has('user')) ?
            User::whereSlug(request('user'))->first() : null;
        if ($user) {
            $vendor->load([
                'transactions' => function ($query) use ($user) {
                    $query->where('owner_id', '=', $user->id);
                },
                'cards.transactions' => function ($query) use ($user) {
                    $query->where('owner_id', '=', $user->id);
                }
            ]);
        } else {
            $vendor->load('transactions', 'cards.transactions.owner');
        }

        return view('vendors.show', compact('vendor', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('vendors')->ignore($vendor->id)],
            'url' => ['nullable', 'string', 'max:255'],
        ]);

        $vendor->update($data);

        session()->flash('flash', ['message' => $vendor->name . ' was successfully updated!', 'level' => 'success']);

        return redirect(route('vendors.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        session()->flash('flash', ['message' => $vendor->name . ' was successfully deleted from the database!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('vendors.index'));
    }
}
