<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\Order\PurchasedListRequest;
use Illuminate\Http\Request;
use App\Models\Order\PurchasedList;
use App\Models\Order\OrderedList;
use App\Services\Order\UnregisteredListService;
use Carbon\Carbon;

class PurchasedListService
{
    private $unregisteredListService;

    public function __construct(UnregisteredListService $unregisteredListService)
    {
        $this->unregisteredListService = $unregisteredListService;
    }

    public function update(PurchasedListRequest $request, PurchasedList $purchasedList)
    {
        try {
            $purchasedList = DB::transaction(function () use ($request, $purchasedList) {
                $purchasedList->job_id = $request->job_id;
                $purchasedList->thing_id = $request->thing_id? $request->thing_id : null;
                $purchasedList->supplier_id = $request->supplier_id? $request->supplier_id : null;
                $purchasedList->lot_size = $request->lot_size;
                $purchasedList->lot_amount = $request->lot_amount;
                $purchasedList->lot_unit_id = $request->lot_unit_id;
                $purchasedList->unit_price = $request->unit_price;
                $purchasedList->depository = $request->depository;
                $purchasedList->purchase_at = $request->purchase_at;
                $purchasedList->save();

                return PurchasedList::find($purchasedList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $purchasedList;
    }

    public function receive(OrderedList $orderedList)
    {
        try {
            $purchasedList = DB::transaction(function () use ($orderedList)
            {
                $purchasedList = new PurchasedList;
                $purchasedList->job_id = $orderedList->job_id;
                $purchasedList->thing_id = $orderedList->thing_id;
                $purchasedList->supplier_id = $orderedList->supplier_id;
                $purchasedList->lot_size = $orderedList->lot_size;
                $purchasedList->lot_amount = $orderedList->lot_amount;
                $purchasedList->lot_unit_id = $orderedList->lot_unit_id;
                $purchasedList->unit_price = $orderedList->unit_price;
                $purchasedList->depository = $orderedList->depository;
                $purchasedList->purchase_at = $orderedList->purchase_at;
                $purchasedList->requested_user_id = $orderedList->requested_user_id;
                $purchasedList->requested_at = $orderedList->requested_at;
                $purchasedList->ordered_user_id = $orderedList->ordered_user_id;
                $purchasedList->ordered_at = $orderedList->created_at;
                $purchasedList->received_user_id = auth()->user()->id;

                $purchasedList->save();

                return PurchasedList::find($purchasedList->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $purchasedList;
    }
}
