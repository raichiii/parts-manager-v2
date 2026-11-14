<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\UnregisteredList;
use Illuminate\Http\Request;

class UnregisteredListController extends Controller
{
    public function index()
    {
        $unregisteredLists = UnregisteredList::all();
        return view('order.unregisteredLists.index', compact('unregisteredLists'));
    }
}
