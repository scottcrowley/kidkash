@extends('layouts.app')

@section('title')
    Add User - KidKash
@endsection

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header font-semibold">Add a new User</div>

        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-4 text-left md:text-right">Name</label>

                    <div class="col-6">
                        <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-4 text-left md:text-right">E-Mail Address</label>

                    <div class="col-6">
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-4 text-left md:text-right">Password</label>

                    <div class="col-6">
                        <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-4 text-left md:text-right">Confirm Password</label>

                    <div class="col-6">
                        <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4 flex">
                        <button type="submit" class="btn is-primary">
                            Add User
                        </button>
                        <a href="{{ url()->previous() }}" class="btn ml-2 border border-secondary-300">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
