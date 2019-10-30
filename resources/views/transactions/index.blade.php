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
                    <div class="max-w-sm w-full lg:max-w-3xl lg:flex mb-12 mx-auto shadow-lg rounded">
                        <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                            <div class="mb-8">
                                <div class="text-gray-900 font-bold text-xl mb-2 flex items-center justify-between">
                                    Kid: {{ $transaction->kid->name }}
                                    Vendor: {{ $transaction->vendor->name }}
                                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn is-primary is-xsmall">Edit</a>
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
                    <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no Transactions in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection