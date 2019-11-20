<?php

namespace App\Http\Controllers;

use App\Card;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user, $vendor)
    {
        $where = [];
        if ($user) {
            $where[] = ['owner_id', '=', $user];
        }
        if ($vendor) {
            $where[] = ['vendor_id', '=', $vendor];
        }

        $cards = Card::where($where)->with('owner')->with('vendor')->get();

        return $cards;
    }
}
