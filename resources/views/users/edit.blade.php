@extends('layouts.app')

@section('title')
    Edit User - KidKash
@endsection

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header">
            @if ($user->id == auth()->user()->id)
                Edit Your Profile
            @else
                Edit {{ $user->name }}'s Profile
            @endif
        </div>

        <div class="card-body">
            <avatar :user="{{ $user }}" authenticated-id="{{ auth()->user()->id }}"></avatar>
            <form method="POST" action="{{ route('users.update', $user->slug) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="form-group row">
                    <label for="name" class="col-4 text-left md:text-right">Name</label>

                    <div class="col-6">
                        <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

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
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                        @error('email')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="current_password" class="col-4 text-left md:text-right">Current Password</label>

                    <div class="col-6">
                        <input id="current_password" type="password" class="form-input @error('current_password') is-invalid @enderror" name="current_password">

                        @error('current_password')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-4 text-left md:text-right">New Password</label>

                    <div class="col-6">
                        <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password">

                        @error('password')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-4 text-left md:text-right">Confirm Password</label>

                    <div class="col-6">
                        <input id="password_confirmation" type="password" class="form-input" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4 flex items-center">
                        <button type="submit" class="btn is-primary">
                            Update
                        </button>
                        <a href="{{ (! auth()->user()->is_authorized_parent) ? route('home') : route('users.index') }}" class="btn ml-2 border border-secondary-300">
                            Done
                        </a>
                        @can('delete', $user)
                            <div class="ml-auto">
                                <delete-confirm-button label="Delete User" classes="btn btn-text" path="/users/{{ $user->id }}" redirect-path="/users" class="inline">
                                    <div slot="title">Are You Sure?</div>  
                                    Are you sure you want to delete {{ $user->name }} from the database? This will delete all transactions associated with them.
                                </delete-confirm-button>
                            </div>
                        @endcan
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
