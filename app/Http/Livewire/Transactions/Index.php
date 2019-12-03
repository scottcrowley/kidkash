<?php

namespace App\Http\Livewire\Transactions;

use App\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 20;

    public function render()
    {
        return view('livewire.transactions.index', [
            'transactions' => Transaction::query()
                ->with('owner')
                ->with('vendor')
                ->latest()
                ->paginate($this->perPage)
        ]);
    }
}
