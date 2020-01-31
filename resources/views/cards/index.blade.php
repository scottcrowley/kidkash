@extends('layouts.app')

@section('title')
    Cards - KidKash
@endsection

@section('head')
    @livewireAssets
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Cards</p>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @livewire('cards.index')
            </div>
        </div>
    </div>
</div>
@endsection