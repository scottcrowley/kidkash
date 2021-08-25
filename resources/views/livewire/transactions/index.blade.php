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
            class="block w-1/2 mb-4 px-3 py-1 rounded-md leading-5 placeholder-gray-500 focus:placeholder-gray-300 sm:text-sm transition duration-150 ease-in-out"
            placeholder="Search"
            type="search">
    </div>
    @forelse ($transactions as $transaction)
        <div class="max-w-sm w-full lg:max-w-3xl mb-4 sm:mb-6 mx-auto shadow-lg rounded">
            <div class="bg-white border rounded-b rounded-l lg:rounded-b-none lg:rounded-r px-4 py-2 flex flex-col justify-between leading-normal">
                <div class="mb-4">
                    <div class="mb-2 flex items-center">
                        <div class="w-10 h-10 mr-3 overflow-hidden rounded-full border border-secondary-700">
                            <img src="/{{ $transaction->owner->avatar_path ?: 'storage/avatars/default.jpg' }}" class="w-full h-full object-cover" />
                        </div>
                        <p class="activity-label flex-1 leading-tight">
                            <span class="text-xs">
                                {{ $transaction->created_at->format('l, m/d/y g:i a') }} </br>
                            </span>
                            {!! $transaction->activity_label !!}
                        </p>
                    </div>
                    <div class="block lg:flex items-center">
                        <p class="flex-1 text-gray-700 text-base">{{ $transaction->description }}</p>
                        <p class="font-bold text-2xl lg:text-3xl text-center lg:text-right mt-4 lg:mt-0">
                            <span>{{ ($transaction->type == 'use') ? '-' : '' }}</span>
                            <span>$ {{ $transaction->modified_amount }}</span>
                        </p>
                    </div>
                </div>
                <div class="lg:text-right">
                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn is-primary lg:is-xsmall block lg:inline lg:px-3 lg:py-1 lg:leading-normal lg:text-xs">Edit</a>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no Transactions in the database.</p>
    @endforelse
</div>
