@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="card">
        <div class="card-header flex justify-between">
            <p class="text-3xl">Kids</p>
            <a href="{{ route('kids.create') }}" class="btn is-primary is-xsmall self-center">Create New</a>
        </div>

        <div class="card-body">
            <div class="mx-6 mt-8">
                @forelse ($kids as $kid)
                    <div class="max-w-sm w-full lg:max-w-3xl lg:flex mb-12 mx-auto shadow-lg rounded">
                        <div class="w-full h-64 lg:w-48 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden bg-secondary-100">
                            @if ($kid->has_avatar)
                                <img src="{{ $kid->avatar_path }}" alt="{{ $kid->name }}" class="w-full h-full object-cover" />
                            @endif
                        </div>
                        <div class="bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col lg:flex-grow justify-around leading-normal">
                            <div class="mb-4 lg:mb-8">
                                <div class="text-gray-700 font-bold text-4xl mb-2 block lg:flex items-center lg:justify-between">
                                    <div>
                                        <a href="{{ route('kids.show', $kid->slug) }}" class="hover:underline hover:text-gray-800">
                                            {{ $kid->name }}
                                        </a>
                                    </div>
                                    <p class="font-bold text-3xl text-center lg:text-right mt-2 lg:mt-0">
                                        <span>{{ (($kid->transaction_totals < 0) ? '- ' : '').' $ '.(number_format(abs($kid->transaction_totals),2)) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="">
                                <p class="font-bold text-base">Recent Activity:</p>
                                <div class="flex flex-col lg:flex-row pt-2 pb-4">
                                    @forelse ($kid->transactions->take(3) as $transaction)
                                        <div class="bg-gray-200 rounded-full mb-2 lg:mb:0 lg:mr-2 px-3 py-1 text-sm font-semibold text-gray-700 text-center">
                                            {!! $transaction->owner_activity_label !!}
                                        </div>
                                    @empty
                                        <p>No transactions found</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="lg:text-right">
                                <a 
                                    href="{{ route('kids.edit', $kid->slug) }}" 
                                    class="btn is-primary lg:is-xsmall block lg:inline lg:px-3 lg:py-1 lg:leading-normal lg:text-xs"
                                >
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center mb-8 w-full md:w-112 lg:w-120">There are currently no Kids in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection