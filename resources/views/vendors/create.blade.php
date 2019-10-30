@extends('layouts.app')

@section('content')
<div class="col-8">
    <div class="card">
        <div class="card-header">
            Add a new Vendor
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('vendors.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-4 text-left md:text-right">Name</label>

                    <div class="col-6">
                        <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>

                        @error('name')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="url" class="col-4 text-left md:text-right">Website</label>

                    <div class="col-6">
                        <input id="url" type="text" class="form-input @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}">

                        @error('url')
                            <span class="alert-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="offset-4 flex items-center">
                        <button type="submit" class="btn is-primary">
                            Add Vendor
                        </button>
                        <a href="{{ route('vendors.index') }}" class="btn ml-2 border border-secondary-300">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
