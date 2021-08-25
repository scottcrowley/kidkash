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
        {{  $vendors->onEachSide(0)->links('vendor.pagination.livewire') }}
    </div>
    <div>
        <input wire:model="search" 
            id="search" 
            class="block w-1/2 mb-4 px-3 py-1 rounded-md leading-5 placeholder-gray-500 focus:placeholder-gray-300 sm:text-sm transition duration-150 ease-in-out"
            placeholder="Search"
            type="search">
    </div>
    @forelse ($vendors as $vendor)
        <div class="max-w-sm w-full lg:max-w-3xl mb-4 sm:mb-6 mx-auto shadow-lg rounded">
            <div class="bg-white rounded-b rounded-l lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-around leading-normal">
                <div class="mb-8">
                    <div class="text-gray-700 font-bold text-2xl mb-2 block lg:flex items-center lg:justify-between">
                        <div class="truncate pr-0 lg:pr-3 flex items-center">
                            <a href="{{ route('vendors.show', $vendor->slug) }}" class="hover:underline hover:text-gray-800">
                                {{ $vendor->name }}
                            </a>
                            @if (! is_null($vendor->url))
                                <a href="{{ $vendor->url }}" class="text-gray-700 hover:text-gray-900 ml-1">
                                    <svg class="fill-current h-5 w-5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">
                                        <path d="M75.1,58.8h5.4v16.3c0,3-2.4,5.4-5.4,5.4H20.9c-3,0-5.4-2.4-5.4-5.4V20.9c0-3,2.4-5.4,5.4-5.4 h16.3v5.4H20.9v54.2h54.2V58.8z M48,15.5l12.2,12.2L42.6,45.3l8.1,8.1l17.6-17.6L80.5,48V15.5H48z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <p class="font-bold text-3xl text-center lg:text-right mt-2 lg:mt-0">
                            <span>{{ (($vendor->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($vendor->transaction_totals),2)) }}</span>
                        </p>
                    </div>
                </div>
                <div>
                    <p class="font-semibold">Recent Activity:</p>
                    <div class="flex flex-col lg:flex-row pt-2 pb-4">
                        @forelse ($vendor->transactions->take(4) as $transaction)
                            <div class="bg-gray-200 rounded-full mb-2 lg:mb:0 lg:mr-2 px-3 py-1 text-sm font-semibold text-gray-700 text-center">
                                {!! $transaction->vendor_activity_label !!}
                            </div>
                        @empty
                            <p>None</p>
                        @endforelse
                    </div>
                </div>
                <div class="lg:text-right">
                    <a href="{{ route('vendors.edit', $vendor->slug) }}" class="btn is-primary lg:is-xsmall block lg:inline lg:px-3 lg:py-1 lg:leading-normal lg:text-xs">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no Vendors in the database.</p>
    @endforelse
</div>
