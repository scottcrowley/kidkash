<?php

namespace App\Http\Livewire\Users;

use App\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $user;
    public $perPage = 20;
    public $search;
    protected $queryString = ['search'];

    public function updatingSearch() 
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = (strlen($this->search) > 2) ? $this->search : null;
        $special = (substr($search, 0, 1) == ':') ? (str_contains(':added', $search) ? '>' : '<') : false;
        return view('livewire.users.show', [
            'transactions' => Transaction::query()
                ->with('vendor')
                ->where('owner_id', $this->user)
                ->when($special !== false, function ($query) use($special) {
                    return $query->where('amount', $special, 0);
                })->when($special === false, function ($query) use($search) {
                    return $query->WhereHas('vendor', function($query) use($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
                })->latest()
                ->paginate($this->perPage)
        ]);
    }
}