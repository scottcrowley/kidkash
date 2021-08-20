@extends('layouts.app')

@section('title')
    Transactions - KidKash
@endsection

@section('head')
    @livewireStyles
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <p class="text-3xl">Transactions</p>
            <div>
                <a href="{{ route('transactions.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
                <a href="{{ route('transfers.create') }}" class="btn is-primary is-xsmall self-center">Transfer</a>
            </div>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @livewire('transactions.index')
            </div>
        </div>
    </div>
</div>
@endsection

@section('body-end')
    @livewireScripts
@endsection