@extends('layouts.app')

@section('title')
    New Transaction - KidKash
@endsection

@section('content')
<div class="col-10">
    <div class="card">
        <div class="card-header font-semibold">Add a new Transaction</div>

        <div class="card-body">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <transaction-form 
                    action="create"
                    :transaction="{{ Session::hasOldInput() ? json_encode(Session::getOldInput()) : $transaction }}"
                    :owners="{{ $owners }}"
                    :vendors="{{ $vendors }}"
                    :cards="{{ $cards }}"
                    :preselected-card="{{ $preselectedCard }}"
                    :errors="{{ $errors->toJson() }}"
                    redirect-path="{{ url()->previous() }}"
                ></transaction-form>
            </form>
        </div>
    </div>
</div>
@endsection
