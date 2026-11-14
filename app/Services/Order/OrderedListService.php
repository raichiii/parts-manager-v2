<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\Order\OrderedListRequest;
use App\Models\Address\Company;
use App\Models\Order\Job;
use App\Models\Order\LotUnit;
use App\Models\Order\RequestedList;
use App\Models\Order\OrderedList;
use App\Services\Order\UnregisteredListService;
use App\Models\Board\Thing;

class OrderedListService
{
    private $unregisteredListService;

    public function __construct(UnregisteredListService $unregisteredListService)
    {
        $this->unregisteredListService = $unregisteredListService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderedListRequest $request)
    {
        try {
            $orderedList = DB::transaction(function () use ($request) {
                $orderedList = new OrderedList;
                $orderedList->job_id = $request->job_id;
                $orderedList->thing_id = $request->thing_id? $request->thing_id : null;
                $orderedList->supplier_id = $request->supplier_id? $request->supplier_id : null;
                $orderedList->lot_size = $request->lot_size;
                $orderedList->lot_amount = $request->lot_amount;
                $orderedList->lot_unit_id = $request->lot_unit_id;
                $orderedList->unit_price = $request->unit_price;
                $orderedList->depository = $request->depository;
                $orderedList->purchase_at = $request->purchase_at;
                $orderedList->requested_user_id = auth()->user()->id;
                $orderedList->save();

                return OrderedList::find($orderedList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $orderedList;
    }

    public function update(OrderedListRequest $request, OrderedList $orderedList)
    {
        try {
            $orderedList = DB::transaction(function () use ($request, $orderedList) {
                $orderedList->job_id = $request->job_id;
                $orderedList->thing_id = $request->thing_id? $request->thing_id : null;
                $orderedList->supplier_id = $request->supplier_id? $request->supplier_id : null;
                $orderedList->lot_size = $request->lot_size;
                $orderedList->lot_amount = $request->lot_amount;
                $orderedList->lot_unit_id = $request->lot_unit_id;
                $orderedList->unit_price = $request->unit_price;
                $orderedList->depository = $request->depository;
                $orderedList->purchase_at = $request->purchase_at;
                $orderedList->save();

                return OrderedList::find($orderedList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $orderedList;
    }

    public function order(RequestedList $requestedList)
    {
        try {
            $orderedList = DB::transaction(function () use ($requestedList)
            {
                $orderedList = new OrderedList;
                $orderedList->job_id = $requestedList->job_id;
                $orderedList->thing_id = $requestedList->thing_id;
                $orderedList->supplier_id = $requestedList->supplier_id;
                $orderedList->lot_size = $requestedList->lot_size;
                $orderedList->lot_amount = $requestedList->lot_amount;
                $orderedList->lot_unit_id = $requestedList->lot_unit_id;
                $orderedList->unit_price = $requestedList->unit_price;
                $orderedList->depository = $requestedList->depository;
                $orderedList->purchase_at = $requestedList->purchase_at;
                $orderedList->requested_user_id = $requestedList->requested_user_id;
                $orderedList->requested_at = $requestedList->created_at;
                $orderedList->ordered_user_id = auth()->user()->id;
                $orderedList->save();

                return $orderedList;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $orderedList;
    }

    public function import($row)
    {
        try {
            [$orderedList, $row] = DB::transaction(function () use ($row) {
                foreach ($row as $key => $value) {
                    if ($value === '') {
                        $row[$key] = null;
                    }
                }

                try{
                    $job_id = Job::where('number', $row['job_no'])->first()->id;
                }catch(\Exception $exception){
                    throw new \Exception("未登録のジョブがあります");
                }
                $thing = Thing::where('model_no', $row['model_no'])->first();
                $thing_id = $thing? $thing->id : null;
                $row['thing_id'] = $thing_id;
                $supplier = Company::where('name', $row['supplier'])->first();
                $supplier_id = $supplier? $supplier->id : null;
                $row['supplier_id'] = $supplier_id;
                try{
                    $lot_unit_id = LotUnit::where('name', $row['lot_unit'])->first()->id;
                }catch(\Exception $exception){
                    throw new \Exception("存在しない袋単位です");
                }

                $orderedList = new OrderedList;
                $orderedList->job_id = $job_id;
                $orderedList->thing_id = $thing_id;
                $orderedList->supplier_id = $supplier_id;
                $orderedList->lot_size = $row['lot_size'];
                $orderedList->lot_amount = $row['lot_amount'];
                $orderedList->lot_unit_id = $lot_unit_id;
                $orderedList->unit_price = $row['unit_price'];
                $orderedList->depository = $row['depository'];
                $orderedList->purchase_at = $row['purchase_at'];
                $orderedList->requested_user_id = auth()->user()->id;
                $orderedList->requested_at = Carbon::now();
                $orderedList->ordered_user_id = auth()->user()->id;
                $orderedList->save();

                return [$orderedList, $row];
            });
        } catch(\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return [$orderedList, $row];
    }
}
