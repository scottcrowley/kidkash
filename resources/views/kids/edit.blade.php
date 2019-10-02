@extends('layouts.app')

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header">Edit {{ $kid->name }}</div>

        <div class="card-body">
            <form method="POST" action="{{ route('kids.update', $kid->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-group row">
                    <label for="name" class="col-4 text-left md:text-right">Name</label>

                    <div class="col-6">
                        <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name', $kid->name) }}" required autocomplete="name" autofocus>

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
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $kid->email) }}" required autocomplete="email">

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
                    <label for="password_confirm" class="col-4 text-left md:text-right">Confirm Password</label>

                    <div class="col-6">
                        <input id="password_confirm" type="password" class="form-input" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4">
                        <button type="submit" class="btn is-primary">
                            Update Kid
                        </button>

                        <delete-confirm-button label="delete" classes="btn btn-text ml-4" path="/kids/{{ $kid->id }}" redirect-path="/kids" class="inline">
                            <div slot="title">Are You Sure?</div>  
                            Are you sure you want to delete {{ $kid->name }} from the database?
                        </delete-confirm-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
