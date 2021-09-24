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
            <span class="hidden lg:inline">Showing </span><span class="hidden md:inline">{{ $transactions->firstItem().' to '.$transactions->lastItem().' out of '. $transactions->total() }}</span> <span class="hidden xl:inline">Transactions</span>
        </div>
        {{  $transactions->onEachSide(0)->links('vendor.pagination.livewire') }}
    </div>
    <div>
        <input wire:model="search" 
            id="search" 
            class="block w-1/2 px-3 py-1 rounded-md leading-5 placeholder-gray-500 focus:placeholder-gray-300 sm:text-sm transition duration-150 ease-in-out"
            placeholder="Search"
            type="search">
        <div class="text-xs mb-4">(Use :added/:used to filter by amount)</div>
    </div>
    @forelse ($transactions as $transaction)
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
</div>
