@extends('layouts.app')

@section('content')
<div class="w-auto">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Transactions</p>
            <a href="{{ route('transactions.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @forelse ($transactions as $transaction)
                    <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
                        <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                            <div class="mb-8">
                                <div class="mb-2 flex items-center justify-between">
                                    <div class="w-10 h-10 mr-3 overflow-hidden rounded-full border border-secondary-700">
                                        <img src="/{{ $transaction->kid->avatar_path ?: 'storage/avatars/default.jpg' }}" class="w-full h-full object-cover" />
                                    </div>
                                    <p class="activity-label">
                                        {!! $transaction->activity_label !!}
                                    </p>
                                    <div class="flex-1 text-right">
                                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn is-primary is-xsmall">Edit</a>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-base">{{ $transaction->description }}</p>
                            </div>
                            <div class="">
                                <p class="font-bold text-3xl text-right">
                                    <span>{{ ($transaction->type == 'use') ? '-' : '' }}</span>
                                    <span>$ {{ $transaction->modified_amount }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no Transactions in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection