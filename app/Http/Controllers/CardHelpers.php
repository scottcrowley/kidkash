<?php

namespace App\Http\Controllers;

use App\Card;
use Carbon\Carbon;

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

    /**
     * Normalize a given date
     *
     * @param string $date
     * @return string
     */
    protected function normalizeDate($date)
    {
        if ($date == '') {
            return null;
        }

        $date = str_replace(['-', ' ', '.'], '/', $date);

        $dateArray = collect(explode('/', $date));

        $partCount = $dateArray->count();
        $shortDate = ($partCount == 2);
        if (! $partCount) {
            return null;
        }

        if ($shortDate) {
            $dateArray->splice(1, 0, ['01']);
        }

        $tmpDate = '';

        foreach ($dateArray as $pos => $part) {
            $tmpDate .= ($pos != 0) ? '/' : '';
            if (
                ($pos == 0 || ($pos == 1 && ! $shortDate)) &&
                substr($part, 0, 1) != '0' && intval($part) < 10
            ) {
                $tmpDate .= '0';
            }

            if ($pos == 3 && strlen($dateArray->last()) == 2) {
                $tmpDate .= substr(now()->year, 0, 2);
            }
            $tmpDate .= $part;
            $pos++;
        }

        $result = Carbon::parse($tmpDate);

        if ($shortDate) {
            $result = $result->endOfMonth();
        }

        return $result->format('Y-m-d');
    }
}
