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
                <div class="mb-6 flex items-center">
                    <div class="text-gray-600 text-sm">
                        {{ $cards->total() }} Cards found
                    </div>
                    {{  $cards->links() }}
                </div>
                @forelse ($cards as $card)
                    <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
                        <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-around leading-normal">
                            <div class="mb-8">
                                <div class="text-gray-700 font-bold text-2xl mb-2 block lg:flex items-center lg:justify-between">
                                    <div class="truncate pr-0 lg:pr-3 flex items-center">
                                        <a href="{{ route('cards.show', $card->id) }}" class="hover:underline hover:text-gray-800">
                                            {{ $card->number }}
                                        </a>
                                    </div>
                                    <p class="font-bold text-3xl text-center lg:text-right mt-2 lg:mt-0">
                                        {{-- <span>{{ (($card->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($card->transaction_totals),2)) }}</span> --}}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold">Recent Activity:</p>
                                <div class="flex flex-col lg:flex-row pt-2 pb-4">
                                    {{-- @forelse ($card->transactions->take(3) as $transaction)
                                        <div class="bg-gray-200 rounded-full mb-2 lg:mb:0 lg:mr-2 px-3 py-1 text-sm font-semibold text-gray-700 text-center">
                                            {!! $transaction->card_activity_label !!}
                                        </div>
                                    @empty
                                        <p>None</p>
                                    @endforelse --}}
                                </div>
                            </div>
                            <div class="lg:text-right">
                                {{-- <a href="{{ route('cards.edit', $card->slug) }}" class="btn is-primary lg:is-xsmall block lg:inline lg:px-3 lg:py-1 lg:leading-normal lg:text-xs">
                                    Edit
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no cards in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection