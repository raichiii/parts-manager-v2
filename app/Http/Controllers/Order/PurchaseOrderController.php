<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order\RequestedList;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        //
    }

    public function edit(Request $request)
    {
        try{
            if (empty($request->ids)) {
                throw  new \Exception("何も選択されていません");
            }
            $firstRequestedList = RequestedList::find($request->ids[0]);
            $firstSupplierName = $firstRequestedList->supplier? $firstRequestedList->supplier->name :
                ($firstRequestedList->unregisteredSupplier()? $firstRequestedList->unregisteredSupplier()->name : null);
            $requestedLists = [];
            foreach ($request->ids as $id) {
                $requestedList = RequestedList::find($id);
                $supplierFullName = $firstRequestedList->supplier? $firstRequestedList->supplier->full_name :
                    ($firstRequestedList->unregisteredSupplier()? $firstRequestedList->unregisteredSupplier()->name : null);
                $supplierName = $requestedList->supplier? $requestedList->supplier->name :
                    ($requestedList->unregisteredSupplier()? $requestedList->unregisteredSupplier()->name : null);
                if (!$supplierName) {
                    throw  new \Exception("仕入れ先は必須です");
                }
                if ($supplierName !== $firstSupplierName) {
                    throw  new \Exception("仕入れ先が異なります");
                }
                $requestedList->supplierName = $supplierFullName;

                $modelNoName = $requestedList->thing? $requestedList->thing->model_no :
                ($requestedList->unregisteredModelNo()? $requestedList->unregisteredModelNo()->name : null);
                $requestedList->modelNoName = $modelNoName;

                array_push($requestedLists, $requestedList);
            }
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        return view('pdf.edit', compact('requestedLists'));
    }

    public function make(Request $request)
    {
        try{
            $supplierName = $request->supplier_name;
            $carbon = Carbon::now();
            $date = $carbon->format('Y年m月d日');
            $customerName = $request->customer;
            $phone = $request->phone;
            $fax = $request->fax;

            for ($i = 0;$i<count($request->order_no);$i++) {
                $requestedLists[] = [
                    'order_no' => $request->order_no[$i],
                    'name' => $request->name[$i],
                    'model_no' => $request->model_no[$i],
                    'maker' => $request->maker[$i],
                    'number' => $request->number[$i],
                    'purchase_at' => $request->purchase_at[$i],
                    'code' => $request->code[$i],
                    'note' => $request->note[$i],
                ];
            }
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        $pdf = \PDF::loadView('pdf.purchaseOrder',
            compact('requestedLists', 'supplierName', 'date', 'customerName', 'phone', 'fax'))
            ->setPaper('a4', 'landscape');
        $name = '注文書_'.$supplierName.$carbon->format('Ymd').'.pdf';
        return $pdf->stream($name);
    }

}
