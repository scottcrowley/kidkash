@extends('layouts.app')

@section('title')
    Cards - KidKash
@endsection

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Cards</p>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @forelse ($vendors as $vendor)
                    @if ($vendor->cards->isNotEmpty())
                        <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
                            <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-around leading-normal">
                                <div>
                                    <div class="text-gray-700 font-bold text-xl lg:text-2xl mb-2 flex items-center justify-between">
                                        <div class="truncate pr-3">
                                            <a href="{{ route('vendors.show', $vendor->slug) }}" class="hover:underline hover:text-gray-800">
                                                {{ $vendor->name }}
                                            </a>
                                        </div>
                                        <p class="font-bold text-right mt-0">
                                            <span>$ {{ number_format($vendor->card_balance_totals, 2) }}</span>
                                        </p>
                                    </div>
                                    @foreach ($vendor->cards as $card)
                                        <div class="text-gray-700 font-semibold text-sm lg:text-base mb-2 py-1 px-3 flex items-center justify-between hover:bg-gray-200 hover:text-gray-800">
                                            <div class="truncate pr-3 flex">
                                                <a href="{{ route('cards.show', $card->id) }}" class="hover:underline hover:text-gray-800">{{ $card->number }}</a>
                                                <span class="text-sm ml-1">({{ $card->owner_names }})</span>
                                            </div>
                                            <p class="text-right mt-0">
                                                <span>$ {{ number_format($card->balance, 2) }}</span>
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-center w-full">There are currently no cards in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection