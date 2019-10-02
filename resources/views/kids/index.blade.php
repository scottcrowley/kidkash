@extends('layouts.app')

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header">Kids</div>

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