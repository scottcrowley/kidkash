<?php

namespace App\Http\Livewire\Cards;

use App\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function render()
    {
        return view('livewire.cards.index', [
            'vendors' => Vendor::query()
                ->with('cards')
                ->with('cards.transactions.owner')
                ->orderBy('name')
                ->paginate($this->perPage)
        ]);
    }
}
