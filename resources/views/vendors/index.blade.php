@extends('layouts.app')

@section('title')
    Vendors - KidKash
@endsection

@section('head')
    @livewireStyles
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Vendors</p>
            <a href="{{ route('vendors.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @livewire('vendors.index')
        </div>
    </div>
</div>
@endsection

@section('body-end')
    @livewireScripts
@endsection