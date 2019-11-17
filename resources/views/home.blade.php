@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="card w-full ">
        <div class="card-header flex justify-between items-center">
            <p class="text-3xl">Dashboard</p>
            <p class="text-2xl text-right">{{ (($user->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($user->transaction_totals),2)) }}</p>
        </div>

        <div class="card-body" style="padding-left: 0; padding-right: 0;">
            <content-drawers title="Balances" :open-default="true">
                @forelse ($user->vendors_list as $vendor)
                    <div class="flex items-center justify-between mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700">
                        <div>{{ $vendor->name }}</div>
                        <div>{{ (($vendor->owner_transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($vendor->owner_transaction_totals),2)) }}</div>
                    </div>
                @empty
                    <p>No Vendors found</p>
                @endforelse
            </content-drawers>
            <content-drawers title="Transactions">
                @forelse ($user->transactions as $transaction)
                    <div class="px-3 leading-loose text-base text-gray-700">{!! $transaction->owner_activity_label !!} {{ $transaction->updated_at->diffForHumans() }}</div>
                @empty
                    <p>No Transactions found</p>
                @endforelse
            </content-drawers>
        </div>
    </div>
</div>
@endsection