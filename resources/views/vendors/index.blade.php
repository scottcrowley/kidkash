@extends('layouts.app')

@section('title')
    Vendors - KidKash
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
                <div class="mb-6 flex items-center">
                    <div class="text-gray-600 text-sm">
                        {{ $vendors->total() }} Vendors found
                    </div>
                    {{  $vendors->links() }}
                </div>
                @forelse ($vendors as $vendor)
                    <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
                        <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-around leading-normal">
                            <div class="mb-8">
                                <div class="text-gray-700 font-bold text-2xl mb-2 block lg:flex items-center lg:justify-between">
                                    <div class="truncate pr-0 lg:pr-3 flex items-center">
                                        <a href="{{ $vendor->url }}" class="text-gray-700 hover:text-gray-900 mr-1">
                                            <svg class="fill-current h-5 w-5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">
                                                <path d="M75.1,58.8h5.4v16.3c0,3-2.4,5.4-5.4,5.4H20.9c-3,0-5.4-2.4-5.4-5.4V20.9c0-3,2.4-5.4,5.4-5.4 h16.3v5.4H20.9v54.2h54.2V58.8z M48,15.5l12.2,12.2L42.6,45.3l8.1,8.1l17.6-17.6L80.5,48V15.5H48z"/>
                                            </svg>
                                        </a>
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