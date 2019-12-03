<?php

namespace App\Http\Livewire\Vendors;

use App\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function render()
    {
        return view('livewire.vendors.index', [
            'vendors' => Vendor::query()
                ->with('transactions')
                ->orderBy('name')
                ->paginate($this->perPage)
        ]);
    }
}
