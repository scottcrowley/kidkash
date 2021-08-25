<?php

namespace App\Http\Livewire\Vendors;

use App\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search;

    public function updatingSearch() 
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = (strlen($this->search) > 2) ? $this->search : null;
        return view('livewire.vendors.index', [
            'vendors' => Vendor::query()
                ->with('transactions')
                ->where('name', 'like', '%'.$search.'%')
                ->orderBy('name')
                ->paginate($this->perPage)
        ]);
    }
}
