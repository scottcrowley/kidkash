@extends('layouts.app')

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header flex">
            Kids
            <a href="{{ route('kids.create') }}" class="btn is-primary is-xsmall ml-auto">Create New</a>
        </div>

        <div class="card-body">
            @forelse ($kids as $kid)
                <p>{{ $kid->name }}</p>
            @empty
                <p>There are currently not Kids in the database.</p>
                
            @endforelse
        </div>
    </div>
</div>
@endsection