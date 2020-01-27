@extends('layouts.app')

@section('title')
    Card Detail - KidKash
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <p class="text-2xl flex items-center">
                {{ $card->number }}
                @if ($card->expiration)
                    <span class="ml-2 text-base {{ $card->expiration_alert }}">{{ 'Exp: '.$card->expiration->format('M Y') }}</span>
                @endif
            </p>
            <p class="text-2xl text-right">$ {{ number_format($card->balance, 2) }}</p>
        </div>

        <div class="card-body" style="padding-left: 0; padding-right: 0;">
            <div class="mb-2 text-2xl font-semibold text-center">
                    <a href="{{ route('vendors.show', $card->vendor->slug) }}" class="hover:underline hover:text-gray-800">
                        {{ $card->vendor->name }}
                    </a>
            </div>
            <content-drawers title="User Balances" :open-default="true">
                @forelse ($card->owners as $owner)
                    <div class="flex items-center justify-between mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700 hover:bg-gray-200 hover:text-gray-800">
                        <div>
                            <a href="{{ route('users.show', $owner->slug) }}" class="hover:underline hover:text-gray-800">
                                {{ $owner->name }}
                            </a>
                        </div>
                        <div>{{ number_format($owner->card_transaction_totals, 2) }}</div>
                    </div>
                @empty
                    <p>No Users found</p>
                @endforelse
            </content-drawers>
            <content-drawers title="Transaction History" :open-default="true">
                @forelse ($card->transactions as $transaction)
                    <div class="px-3 leading-loose text-sm text-gray-700 flex items-center hover:bg-gray-200 hover:text-gray-800">
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="mr-2">
                            <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                            </svg>
                        </a>
                        <div class="truncate flex-1">
                            {!! $transaction->vendor_activity_label !!} 
                            {{ $transaction->updated_at->diffForHumans() . ($transaction->description != '' ? ' - '. $transaction->description : '') }}
                        </div>
                        <div class="ml-1 font-semibold">
                            {{ (($transaction->amount < 0) ? '-' : '+').' '.number_format(abs($transaction->amount), 2) }} 
                        </div>
                    </div>
                @empty
                    <p>No Transactions found</p>
                @endforelse
            </content-drawers>
            <div class="mt-4 text-right mr-6">
                <a href="{{ url()->previous() }}" class="btn border border-secondary-300">Done</a>
            </div>
        </div>
    </div>
</div>
@endsection