<?php

namespace App\Http\Controllers;

use App\Card;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $vendor
     * @return Collection
     */
    public function index($vendor)
    {
        $where = [];
        if ($vendor) {
            $where[] = ['vendor_id', '=', $vendor];
        }

        $cards = Card::where($where)->with('vendor')->get();

        return $cards;
    }
}
