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
            Showing {{ $vendors->firstItem().' to '.$vendors->lastItem().' out of '. $vendors->total() }} Vendors
        </div>
        {{  $vendors->links('vendor.pagination.livewire') }}
    </div>
    @forelse ($vendors as $vendor)
        <div class="max-w-sm w-full lg:max-w-3xl mb-12 mx-auto shadow-lg rounded">
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
