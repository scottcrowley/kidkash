<?php

namespace App\Http\Controllers;

use App\Card;

trait CardHelpers
{
    /**
     * Retrieves all cards with vendor & transactions relationships
     *
     * @param string $vendors
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function cards($vendors)
    {
        $query = Card::query();
        $where = [];
        if ($vendors) {
            $query->whereIn('vendor_id', explode(',', $vendors));
        }
        return $query->with('vendor')->with('transactions');
    }

    /**
     * Gets a list of Cards that only have a balance
     *
     * @param string $vendors
     * @return \Illuminate\Support\Collection
     */
    public function getActiveCardList($vendors = null)
    {
        return $this->cards($vendors)->get()->sortBy('vendor.name')
            ->each(function ($card) {
                $card->append('balance');
            })
            ->filter(function ($card) {
                return $card->balance != 0;
            })
            ->values();
    }

    /**
     * Gets a list of all Cards
     *
     * @param string $vendors
     * @return \Illuminate\Support\Collection
     */
    public function getCardList($vendors = null)
    {
        return $this->cards($vendors)->get()->sortBy('vendor.name')
            ->each(function ($card) {
                $card->append('balance');
            })
            ->values();
    }
}
