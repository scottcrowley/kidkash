<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KidsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kids = User::where('is_kid', true)->orderBy('name')->get();

        return view('kids.index', compact('kids'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kids.create');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_kid'] = true;

        User::create($data);

        session()->flash('flash', ['message' => $data['name'].' added successfully!', 'level' => 'success']);

        return redirect(route('kids.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $kid
     * @return \Illuminate\Http\Response
     */
    public function show(User $kid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $kid
     * @return \Illuminate\Http\Response
     */
    public function edit(User $kid)
    {
        return view('kids.edit', compact('kid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $kid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $kid)
    {
        if (! $request->filled('current_password')) {
            $input = $request->except(['current_password', 'password', 'password_confirmation']);
        } else {
            $input = $request->all();
        }

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($kid)],
            'current_password' => [
                'sometimes',
                function ($attribute, $value, $fail) use ($kid) {
                    $check = Hash::check($value, $kid->password);
                    if (! $check) {
                        $fail('Your current password is invalid.');
                    }
                }],
            'password' => ['required_with:current_password', 'different:current_password', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
        }

        $data = Arr::except($validator->attributes(), ['current_password', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        }

        $kid->update($data);

        session()->flash('flash', ['message' => $kid->name.' was successfully updated!', 'level' => 'success']);

        return redirect(route('kids.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $kid
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $kid)
    {
        $kid->delete();

        session()->flash('flash', ['message' => $kid->name.' was successfully deleted from the database!', 'level' => 'success']);

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('kids.index'));
    }
}
