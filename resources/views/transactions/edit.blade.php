@extends('layouts.app')

@section('title')
    Edit Transaction - KidKash
@endsection

@section('content')
<div class="col-10">
    <div class="card">
        <div class="card-header">Edit a Transaction</div>

        <div class="card-body">
            <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                @csrf
                @method('patch')

                <transaction-form 
                    action="update"
                    :transaction="{{ Session::hasOldInput() ? json_encode(Session::getOldInput()) : $transaction }}"
                    :owners="{{ $owners }}"
                    :vendors="{{ $vendors }}"
                    :cards="{{ $cards }}"
                    :errors="{{ $errors->toJson() }}"
                ></transaction-form>
            </form>
            {{-- <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                @csrf
                @method('patch')

                <div class="form-group row">
                    <label for="owner_id" class="col-4 w-1/3 text-left md:text-right">Owner</label>

                    <div class="col-6 w-2/3">
                        <div class="relative">
                            <select name="owner_id" class="w-full" required>
                                <option value=''>Choose an Owner</option>
                                @forelse ($owners as $owner)
                                    <option 
                                        value="{{ $owner->id }}" 
                                        {{ (
                                            (old('owner_id') != '' && $owner->id == old('owner_id')) || 
                                            (old('owner_id') == '' && $owner->id == $transaction->owner_id)
                                        ) ? 'selected': '' }}
                                    >{{ $owner->name }}</option>
                                @empty
                                    <option value=''>No owners in Database</option>
                                @endforelse
                            </select>
                            <div class="select-menu-icon">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                        @error('owner_id')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="vendor_id" class="col-4 w-1/3 text-left md:text-right">Vendor</label>

                    <div class="col-6 w-2/3">
                        <div class="relative">
                            <select name="vendor_id" class="w-full" required>
                                <option value=''>Choose a Vendor</option>
                                @forelse ($vendors as $vendor)
                                    <option 
                                        value="{{ $vendor->id }}" 
                                        {{ (
                                            (old('vendor_id') != '' && $vendor->id == old('vendor_id')) || 
                                            (old('vendor_id') == '' && $vendor->id == $transaction->vendor_id)
                                        ) ? 'selected': '' }}
                                    >{{ $vendor->name }}</option>
                                @empty
                                    <option value=''>No Vendors in Database</option>
                                @endforelse
                            </select>
                            <div class="select-menu-icon">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>

                        @error('vendor_id')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type" class="col-4 w-1/3 text-left md:text-right">Transaction Type</label>

                    <div class="col-6 w-2/3">
                        <div class="relative">
                            <select name="type" class="w-full" required>
                                <option value=''>Choose a Type</option>
                                <option 
                                    value="add" 
                                    {{ (
                                        (old('type') != '' && old('type') == 'add') || 
                                        (old('type') == '' && $transaction->type == 'add')
                                    ) ? 'selected': '' }}
                                >Adding Money</option>
                                <option 
                                    value="use" 
                                    {{ (
                                        (old('type') != '' && old('type') == 'use') || 
                                        (old('type') == '' && $transaction->type == 'use')
                                    ) ? 'selected': '' }}
                                >Using Money</option>
                            </select>
                            <div class="select-menu-icon">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>

                        @error('type')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-4 w-1/3 text-left md:text-right">Description</label>

                    <div class="col-6 w-2/3">
                        <textarea id="description" rows="4" name="description" class="form-input @error('description') is-invalid @enderror">{{ old('description', $transaction->description) }}</textarea>

                        @error('description')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="number" class="col-4 w-1/3 text-left md:text-right">Card Number</label>

                    <div class="col-6 w-2/3">
                        <input id="number" type="text" class="form-input @error('number') is-invalid @enderror" name="number" value="{{ old('number', ($transaction->card) ? $transaction->card->number : '') }}">

                        @error('number')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pin" class="col-4 w-1/3 text-left md:text-right">Card Pin</label>

                    <div class="col-6 w-2/3">
                        <input id="pin" type="text" class="form-input @error('pin') is-invalid @enderror" name="pin" value="{{ old('pin', ($transaction->card) ? $transaction->card->pin : '') }}">

                        @error('pin')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="amount" class="col-4 w-1/3 text-left md:text-right">Amount</label>

                    <div class="col-6 w-2/3">
                        <input id="amount" type="text" class="form-input @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', abs($transaction->amount)) }}" required>

                        @error('amount')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4 flex">
                        <button type="submit" class="btn is-primary">
                            Update Transaction
                        </button>
                        <a href="{{ url()->previous() }}" class="btn ml-2 border border-secondary-300">
                            Cancel
                        </a>
                        <div class="ml-auto">
                            <delete-confirm-button label="delete" classes="btn btn-text" path="/transactions/{{ $transaction->id }}" redirect-path="/transactions" class="inline">
                                <div slot="title">Are You Sure?</div>  
                                Are you sure you want to delete this transaction from the database?
                            </delete-confirm-button>
                        </div>
                    </div>
                </div>
            </form> --}}
        </div>
    </div>
</div>
@endsection
