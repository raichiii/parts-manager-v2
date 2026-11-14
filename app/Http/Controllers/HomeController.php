<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order\RequestedList;
use App\Models\Board\PrintCircuitBoard;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $requestedLists = RequestedList::all();
        $suppliers = [];
        foreach ($requestedLists as $requestedList) {
            if ($requestedList->supplier) {
                $suppliers[] = $requestedList->supplier->name;
            }else if ($requestedList->unregisteredSupplier()) {
                $suppliers[] = $requestedList->unregisteredSupplier()->name;
            }
        }
        $suppliers = array_unique($suppliers);

        $printCircuitBoards = PrintCircuitBoard::where('valid_years', '>', '0')->get();
        $next_month = Carbon::now()->addMonth();
        $deadlineBoards = [];
        foreach ($printCircuitBoards as $printCircuitBoard) {
            $ordered_at = new Carbon($printCircuitBoard->ordered_at);
            $rest_time = $ordered_at->addYears($printCircuitBoard->valid_years);
            if ($next_month->gt($rest_time)) {
                $printCircuitBoard->deadline = $rest_time->format('m/d');
                $deadlineBoards[] = $printCircuitBoard;
            }
        }

        return view('home', compact('suppliers', 'deadlineBoards'));
    }
}
