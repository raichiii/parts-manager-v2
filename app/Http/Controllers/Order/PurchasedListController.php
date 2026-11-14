<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order\PurchasedList;
use App\Http\Requests\Order\PurchasedListRequest;
use App\Services\Order\PurchasedListService;
use App\Services\Order\UnregisteredModelService;

class PurchasedListController extends Controller
{
    private $purchasedListService;
    private $unregisteredModelService;

    public function __construct(PurchasedListService $purchasedListService,
        UnregisteredModelService $unregisteredModelService)
    {
        $this->purchasedListService = $purchasedListService;
        $this->unregisteredModelService = $unregisteredModelService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchasedLists = PurchasedList::with(['thing', 'part', 'printCircuitBoard', 'other', 'job', 'supplier', 'requestedUser',
            'orderedUser', 'receivedUser', 'unregisteredModels'])->orderBy('id', 'desc')->paginate(1000);
        return view('order.purchasedLists.index', compact('purchasedLists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchasedList = PurchasedList::with(['thing', 'part', 'printCircuitBoard', 'other',
            'job', 'supplier', 'unregisteredModels'])->get()->find($id);
        $purchasedList->model_no = $purchasedList->thing? $purchasedList->thing->model_no :
            ($purchasedList->unregisteredModelNo()? $purchasedList->unregisteredModelNo()->name : null);
        $purchasedList->supplierName = $purchasedList->supplier? $purchasedList->supplier->name :
            ($purchasedList->unregisteredSupplier()? $purchasedList->unregisteredSupplier()->name : null);
        return view('order.purchasedLists.edit', compact('purchasedList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurchasedListRequest $request, PurchasedList $purchasedList)
    {
        try {
            $purchasedList = $this->purchasedListService->update($request, $purchasedList);
            $this->unregisteredModelService->confirm($purchasedList, $request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('purchasedLists.index')->with('success', '変更を保存しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchasedList = PurchasedList::find($id);
        $purchasedList->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multiDelete(Request $request)
    {
        try{
            if (empty($request->ids)) {
                throw  new \Exception("何も選択されていません");
            }
            DB::transaction(function () use ($request) {
                foreach ($request->ids as $id) {
                    $purchasedList = PurchasedList::find($id);
                    $this->unregisteredModelService->list_delete($purchasedList);
                    $this->destroy($id);
                }
            });
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('purchasedLists.index')->with('danger', '削除が完了しました');
    }
}
