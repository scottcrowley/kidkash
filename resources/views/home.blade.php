@extends('layouts.app')

@section('title')
    Dashboard - KidKash
@endsection

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
                    <div class="flex items-center justify-between mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700 hover:bg-gray-200 hover:text-gray-800">
                        <div>{{ $vendor->name }}</div>
                        <div>{{ (($vendor->owner_transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($vendor->owner_transaction_totals),2)) }}</div>
                    </div>
                @empty
                    <p>No Vendors found</p>
                @endforelse
            </content-drawers>
            <content-drawers title="Card Balances" :open-default="false">
                @forelse ($user->cards_list as $vendor => $cards)
                    <div class="mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700">
                        <div class="flex items-center justify-between">
                            <div>{{ $vendor }}</div>
                            <div>{{ '$ '.(number_format(abs($cards->sum('card_balance')),2)) }}</div>
                        </div>
                        <div class="mt-2">
                            @foreach ($cards as $card)
                                <div class="flex items-center justify-between pl-4 pr-8 py-2 text-sm hover:bg-gray-200 hover:text-gray-800">
                                    <div>{{ $card->number }}</div>
                                    <div>{{ (($card->card_balance < 0) ? '- ' : '').' $ '.(number_format(abs($card->card_balance),2)) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p>No Cards found</p>
                @endforelse
            </content-drawers>
            <content-drawers title="Transactions" :open-default="false">
                @forelse ($user->transactions as $transaction)
                    <div class="px-3 leading-loose text-base text-gray-700 hover:bg-gray-200 hover:text-gray-800">
                        {!! $transaction->owner_activity_label !!} {{ $transaction->updated_at->diffForHumans() }}
                    </div>
                @empty
                    <p>No Transactions found</p>
                @endforelse
            </content-drawers>
        </div>
    </div>
</div>
@endsection