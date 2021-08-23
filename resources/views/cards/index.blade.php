@extends('layouts.app')

@section('title')
    Cards - KidKash
@endsection

@section('head')
    @livewireStyles
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <p class="text-3xl">Cards</p>
            <div>
                <a href="{{ route('cards.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
            </div>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @livewire('cards.index')
            </div>
        </div>
    </div>
</div>
@endsection

@section('body-end')
    @livewireScripts
@endsection