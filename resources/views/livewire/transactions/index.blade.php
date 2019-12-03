<div>
    <div class="mb-6 flex items-center">
        <div class="flex items-center mr-4">
            <p>Per Page:</p>
            <div class="ml-1 relative">
                <select wire:model="perPage" class="w-full">
                    <option>5</option>
                    <option>10</option>
                    <option>20</option>
                    <option>30</option>
                </select>
                <div class="select-menu-icon">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="text-gray-600 text-sm">
            Showing {{ $transactions->firstItem().' to '.$transactions->lastItem().' out of '. $transactions->total() }} Transactions
        </div>
        {{  $transactions->onEachSide(2)->links('vendor.pagination.livewire') }}
    </div>
    @forelse ($transactions as $transaction)
        <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
            <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                <div class="mb-8">
                    <div class="mb-2 flex items-center">
                        <div class="w-10 h-10 mr-3 overflow-hidden rounded-full border border-secondary-700">
                            <img src="/{{ $transaction->owner->avatar_path ?: 'storage/avatars/default.jpg' }}" class="w-full h-full object-cover" />
                        </div>
                        <p class="activity-label flex-1">
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
