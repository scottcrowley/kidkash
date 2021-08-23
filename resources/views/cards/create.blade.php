@extends('layouts.app')

@section('title')
    Edit Card - KidKash
@endsection

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header font-semibold">Add a new Card</div>

        <div class="card-body">
            <p class="text-center warning mb-4"><span class="font-bold">Please note:</span> It is better to add a new card when adding or editing a transaction</p>
            <form method="POST" action="{{ route('cards.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="vendor_id" class="col-4 text-left md:text-right">Vendor</label>

                    <div class="col-6 w-2/3">
                        <div class="relative">
                            <select name="vendor_id" class="w-full @error('vendor_id') is-invalid @enderror" required>
                                @if ($vendors->isNotEmpty())
                                    <option value="0">Choose a Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ (old('vendor_id') && old('vendor_id') == $vendor->id) ? ' selected' : '' }}
                                        >{{ $vendor->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No Vendors in Database</option>
                                @endif
                            </select>
                        </div>

                        @error('vendor_id')
                        <span class="alert-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
                            Add Card
                        </button>
                        <a href="{{ route('cards.index') }}" class="btn ml-2 border border-secondary-300">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
