<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->is_authorized_parent && (url()->previous() == url(route('login')) || url()->previous() == config('app.url'))) {
            return redirect(route('users.index'));
        }

        return view('home', ['user' => auth()->user()]);
    }
}
