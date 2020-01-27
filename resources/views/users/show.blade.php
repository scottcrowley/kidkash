@extends('layouts.app')

@section('title')
    User Detail - KidKash
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <p class="text-3xl">{{ $user->name }}</p>
            <p class="text-2xl text-right">{{ (($user->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($user->transaction_totals),2)) }}</p>
        </div>

        <div class="card-body" style="padding-left: 0; padding-right: 0;">
            <content-drawers title="Vendor Balances" :open-default="true">
                @forelse ($user->vendors_list as $vendor)
                    <div class="flex items-center justify-between mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700 hover:bg-gray-200 hover:text-gray-800">
                        <div>
                            <a href="{{ route('vendors.show', [$vendor->slug, 'user' => $user->slug]) }}" class="hover:underline hover:text-gray-800">
                                {{ $vendor->name }}
                            </a>
                        </div>
                        <div>{{ (($vendor->owner_transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($vendor->owner_transaction_totals),2)) }}</div>
                    </div>
                @empty
                    <p>No Vendors found</p>
                @endforelse
            </content-drawers>
            <content-drawers title="Card Balances" :open-default="{{ $user->cards_list->isNotEmpty() ? 'true' : 'false' }}">
                @forelse ($user->cards_list as $vendor => $cards)
                    <div class="mb-2 lg:mb:0 px-3 py-1 text-lg text-gray-700">
                        <div class="flex items-center justify-between">
                            <div>{{ $vendor }}</div>
                            <div>{{ '$ '.(number_format(abs($cards->sum('card_balance')),2)) }}</div>
                        </div>
                        <div class="mt-2">
                            @foreach ($cards as $card)
                                <div class="flex items-center justify-between pl-4 pr-8 py-2 text-sm hover:bg-gray-200 hover:text-gray-800">
                                    <div class="flex items-center">
                                        <a href="{{ route('cards.show', $card->id) }}" class="hover:underline hover:text-gray-800">
                                            {{ $card->number }}
                                        </a>
                                        @if ($card->expiration)
                                            <span class="ml-2 text-sm {{ $card->expiration_alert }}">{{ 'Exp: '.$card->expiration->format('M Y') }}</span>
                                        @endif
                                    </div>
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
                    <div class="px-3 leading-loose text-sm text-gray-700 flex items-center hover:bg-gray-200 hover:text-gray-800">
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="mr-2">
                            <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                            </svg>
                        </a>
                        <div class="truncate">
                            {!! $transaction->owner_activity_label !!} 
                            {{ $transaction->updated_at->diffForHumans() . ($transaction->description != '' ? ' - '. $transaction->description : '') }}
                        </div>
                    </div>
                @empty
                    <p>No Transactions found</p>
                @endforelse
            </content-drawers>
            <div class="mt-4 text-right mr-6">
                <a href="{{ route('users.index') }}" class="btn border border-secondary-300">Done</a>
            </div>
        </div>
    </div>
</div>
@endsection