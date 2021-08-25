<?php

namespace App\Http\Livewire\Transactions;

use App\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search;

    public function updatingSearch() 
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = (strlen($this->search) > 2) ? $this->search : null;
        return view('livewire.transactions.index', [
            'transactions' => Transaction::query()
                ->with('owner')
                ->with('vendor')
                ->where('description', 'like', '%'.$search.'%')
                ->orWhereHas('vendor', function($query) use($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('owner', function($query) use($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->latest()
                ->paginate($this->perPage)
        ]);
    }
}