<div>
    <div class="mb-6 flex items-center">
        <div class="flex items-center mr-2">
            <p>Per Page:</p>
            <div class="ml-1 relative">
                <select wire:model="perPage" class="w-full py-1 pl-1 pr-7">
                    <option>5</option>
                    <option>10</option>
                    <option>20</option>
                    <option>30</option>
                </select>
            </div>
        </div>
        <div class="text-gray-600 text-sm">
            <span class="hidden lg:inline">Showing </span>{{ $vendors->firstItem().' to '.$vendors->lastItem().' out of '. $vendors->total() }} <span class="hidden lg:inline">Vendors</span>
        </div>
        {{  $vendors->onEachSide(2)->links('vendor.pagination.livewire') }}
    </div>
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
                        @php
                            $vendor->cards = $vendor->cards->sortByDesc('balance');
                        @endphp
                        @foreach ($vendor->cards as $card)
                            <div class="text-gray-700 font-semibold text-sm lg:text-base mb-2 py-1 px-3 flex items-center justify-between hover:bg-gray-200 hover:text-gray-800">
                                <div class="truncate pr-3 flex items-center">
                                    <a href="{{ route('cards.edit', $card->id) }}" class="mr-2">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('cards.show', $card->id) }}" class="hover:underline hover:text-gray-800">{{ $card->number }}</a>
                                    @if ($card->owner_names)
                                        <span class="text-sm ml-1">({{ $card->owner_names }})</span>
                                    @endif
                                    
                                    @if ($card->expiration)
                                        <span class="text-sm ml-1 {{ $card->expiration_alert }}">{{ 'Exp: '.$card->expiration->format('M Y') }}</span>
                                    @endif
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