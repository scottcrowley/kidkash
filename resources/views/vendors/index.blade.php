@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Vendors</p>
            <a href="{{ route('vendors.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @forelse ($vendors as $vendor)
                    <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
                        <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-around leading-normal">
                            <div class="mb-8">
                                <div class="text-gray-700 font-bold text-2xl mb-2 block lg:flex items-center lg:justify-between">
                                    <div class="truncate pr-0 lg:pr-3">
                                        <a href="{{ route('vendors.show', $vendor->slug) }}" class="hover:underline hover:text-gray-800">
                                            {{ $vendor->name }}
                                        </a>
                                    </div>
                                    <p class="font-bold text-3xl text-center lg:text-right mt-2 lg:mt-0">
                                        <span>{{ (($vendor->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($vendor->transaction_totals),2)) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold">Recent Activity:</p>
                                <div class="flex flex-col lg:flex-row pt-2 pb-4">
                                    @forelse ($vendor->transactions->take(8) as $transaction)
                                        <div class="bg-gray-200 rounded-full mb-2 lg:mb:0 lg:mr-2 px-3 py-1 text-sm font-semibold text-gray-700 text-center">
                                            {!! $transaction->vendor_activity_label !!}
                                        </div>
                                    @empty
                                        <p>No transactions found</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="lg:text-right">
                                <a href="{{ route('vendors.edit', $vendor->slug) }}" class="btn is-primary lg:is-xsmall block lg:inline lg:px-3 lg:py-1 lg:leading-normal lg:text-xs">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no Vendors in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection