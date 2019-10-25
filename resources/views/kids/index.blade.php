@extends('layouts.app')

@section('content')
<div class="w-auto">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Kids</p>
            @can('create', auth()->user())
                <a href="{{ route('kids.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
            @endcan
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @forelse ($kids as $kid)
                    <div class="max-w-sm w-full lg:max-w-3xl lg:flex mb-12 mx-auto shadow-lg rounded">
                        <div class="w-full h-64 lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden bg-secondary-100">
                            @if ($kid->has_avatar)
                                <img src="{{ $kid->avatar_path }}" alt="{{ $kid->name }}" class="w-full h-full object-cover" />
                            @endif
                        </div>
                        <div class="bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                            <div class="mb-8">
                                <div class="text-gray-900 font-bold text-xl mb-2 flex items-center justify-between">
                                    {{ $kid->name }}
                                    @can('update', $kid)
                                        <a href="{{ route('kids.edit', $kid->id) }}" class="btn is-primary is-xsmall">Edit</a>
                                    @endcan
                                </div>
                                <p class="text-gray-700 text-base">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus quia, nulla! Maiores et perferendis eaque, exercitationem praesentium nihil.</p>
                            </div>
                            <div class="pr-6">
                                <p>Recent Activity:</p>
                                <div class="pt-2 pb-4">
                                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#photography</span>
                                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#travel</span>
                                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">#winter</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>There are currently not Kids in the database.</p>
                    
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection