@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <p class="text-3xl">{{ $vendor->name }}</p>
            <p class="text-2xl text-right">{{ (($vendor->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($vendor->transaction_totals),2)) }}</p>
        </div>

        <div class="card-body" style="padding-left: 0; padding-right: 0;">
            <content-drawers title="User Balances" :open-default="true">
                @forelse ($vendor->owners_list as $owner)
                    <div class="flex items-center justify-between mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700">
                        <div>{{ $owner->name }}</div>
                        <div>{{ (($owner->vendor_transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($owner->vendor_transaction_totals),2)) }}</div>
                    </div>
                @empty
                    <p>No balances for any Users found</p>
                @endforelse
            </content-drawers>
            <content-drawers title="Transactions" :open-default="true">
                @forelse ($vendor->transactions as $transaction)
                    <div class="px-3 leading-loose text-sm text-gray-700 flex items-center">
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="mr-2">
                            <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                            </svg>
                        </a>
                        <div class="truncate">
                            {!! $transaction->vendor_activity_label !!} 
                            {{ $transaction->updated_at->diffForHumans() . ($transaction->description != '' ? ' - '. $transaction->description : '') }}
                        </div>
                    </div>
                @empty
                    <p>No Transactions found</p>
                @endforelse
            </content-drawers>
            <div class="mt-4 text-right mr-6">
                <a href="{{ route('vendors.index') }}" class="btn border border-secondary-300">Done</a>
            </div>
        </div>
    </div>
</div>
@endsection