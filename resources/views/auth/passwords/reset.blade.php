@extends('layouts.app')

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header">{{ __('Reset Password') }}</div>

        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">
                    <label for="email" class="col-4 text-left md:text-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-6">
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-4 text-left md:text-right">{{ __('Password') }}</label>

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
                    <label for="password-confirm" class="col-4 text-left md:text-right">{{ __('Confirm Password') }}</label>

                    <div class="col-6">
                        <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4">
                        <button type="submit" class="btn is-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
