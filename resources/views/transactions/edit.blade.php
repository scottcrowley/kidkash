@extends('layouts.app')

@section('title')
    Edit Transaction - KidKash
@endsection

@section('content')
<div class="col-10">
    <div class="card">
        <div class="card-header font-semibold">Edit a Transaction</div>

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
                    redirect-path="{{ url()->previous() }}"
                ></transaction-form>
            </form>
        </div>
    </div>
</div>
@endsection
