@extends('layouts.app')

@section('title')
    Edit Card - KidKash
@endsection

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header font-semibold">
            Edit Card {{ $card->number }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('cards.update', $card->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-group row">
                    <label class="col-4 text-left md:text-right">Vendor</label>

                    <div class="col-6 font-semibold">
                        {{ $card->vendor->name }}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="number" class="col-4 text-left md:text-right">Number</label>

                    <div class="col-6">
                        <input id="number" type="text" class="form-input @error('number') is-invalid @enderror" name="number" value="{{ old('number', $card->number) }}" autocomplete="off" required autofocus>

                        @error('number')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pin" class="col-4 text-left md:text-right">Pin or CCV</label>

                    <div class="col-6">
                        <input id="pin" type="text" class="form-input @error('pin') is-invalid @enderror" name="pin" value="{{ old('pin', $card->pin) }}" autocomplete="off">

                        @error('pin')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="expiration" class="col-4 text-left md:text-right">Expiration</label>

                    <div class="col-6">
                        <input id="expiration" type="text" class="form-input @error('expiration') is-invalid @enderror" name="expiration" value="{{ old('expiration', $card->expiration ? $card->expiration->format('m/d/Y') : '') }}">

                        @error('expiration')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4 flex items-center">
                        <button type="submit" class="btn is-primary">
                            Update
                        </button>
                        <a href="{{ route('cards.index') }}" class="btn ml-2 border border-secondary-300">
                            Cancel
                        </a>
                        <div class="ml-auto">
                            <delete-confirm-button label="Delete Card" classes="btn btn-text" path="/cards/{{ $card->id }}" redirect-path="/cards" class="inline">
                                <div slot="title">Are You Sure?</div>  
                                Are you sure you want to delete this card from the database?
                            </delete-confirm-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
