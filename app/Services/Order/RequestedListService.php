<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\Order\RequestedListRequest;
use App\Models\Address\Company;
use App\Models\Order\Job;
use App\Models\Order\LotUnit;
use App\Models\Order\RequestedList;
use App\Services\Order\UnregisteredListService;
use App\Models\Board\Thing;
use Carbon\Carbon;

class RequestedListService
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
    public function store(RequestedListRequest $request)
    {
        try {
            $requestedList = DB::transaction(function () use ($request) {
                $requestedList = new RequestedList;
                $requestedList->job_id = $request->job_id;
                $requestedList->thing_id = $request->thing_id? $request->thing_id : null;
                $requestedList->supplier_id = $request->supplier_id? $request->supplier_id : null;
                $requestedList->lot_size = $request->lot_size;
                $requestedList->lot_amount = $request->lot_amount;
                $requestedList->lot_unit_id = $request->lot_unit_id;
                $requestedList->unit_price = $request->unit_price;
                $requestedList->depository = $request->depository;
                $requestedList->purchase_at = $request->purchase_at;
                $requestedList->requested_user_id = auth()->user()->id;
                $requestedList->save();

                return RequestedList::find($requestedList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $requestedList;
    }

    public function update(RequestedListRequest $request, RequestedList $requestedList)
    {
        try {
            $requestedList = DB::transaction(function () use ($request, $requestedList) {
                $requestedList->job_id = $request->job_id;
                $requestedList->thing_id = $request->thing_id? $request->thing_id : null;
                $requestedList->supplier_id = $request->supplier_id? $request->supplier_id : null;
                $requestedList->lot_size = $request->lot_size;
                $requestedList->lot_amount = $request->lot_amount;
                $requestedList->lot_unit_id = $request->lot_unit_id;
                $requestedList->unit_price = $request->unit_price;
                $requestedList->depository = $request->depository;
                $requestedList->purchase_at = $request->purchase_at;
                $requestedList->save();

                return RequestedList::find($requestedList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $requestedList;
    }

    public function dateFormat($date)
    {
        return date_format(date_create($date), 'Y-m-d');
    }

    public function import($row)
    {
        try {
            [$requestedList, $row] = DB::transaction(function () use ($row) {
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

                $requestedList = new RequestedList;
                $requestedList->job_id = $job_id;
                $requestedList->thing_id = $thing_id;
                $requestedList->supplier_id = $supplier_id;
                $requestedList->lot_size = $row['lot_size'];
                $requestedList->lot_amount = $row['lot_amount'];
                $requestedList->lot_unit_id = $lot_unit_id;
                $requestedList->unit_price = $row['unit_price'];
                $requestedList->depository = $row['depository'];
                $requestedList->purchase_at = $row['purchase_at'];
                $requestedList->requested_user_id = auth()->user()->id;
                $requestedList->save();

                return [$requestedList, $row];
            });
        } catch(\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return [$requestedList, $row];
    }
}
