<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Order\OrderedList;
use App\Http\Requests\Order\OrderedListRequest;
use App\Models\Order\RequestedList;
use App\Services\Order\PurchasedListService;
use App\Services\Order\OrderedListService;
use App\Services\Csv\OrderedListCsvImportService;
use App\Services\Order\UnregisteredModelService;
use App\Services\RedisService;

class OrderedListController extends Controller
{
    private $orderedListService;
    private $purchasedListService;
    private $orderedListCsvImportService;
    private $unregisteredModelService;
    private $redisService;

    public function __construct(OrderedListService $orderedListService,
        PurchasedListService $purchasedListService,
        OrderedListCsvImportService $orderedListCsvImportService,
        UnregisteredModelService $unregisteredModelService,
        RedisService $redisService)
    {
        $this->orderedListService = $orderedListService;
        $this->purchasedListService = $purchasedListService;
        $this->orderedListCsvImportService = $orderedListCsvImportService;
        $this->unregisteredModelService = $unregisteredModelService;
        $this->redisService = $redisService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $orderedLists = json_encode($this->redisService->get_and_set('all_ordered_lists_with_relations', function () {
        //     return OrderedList::with(['thing', 'part', 'printCircuitBoard', 'other',
        //         'job', 'supplier', 'requestedUser', 'orderedUser', 'unregisteredModels'])->get();
        // }));
        $orderedLists =  OrderedList::with(['thing', 'part', 'printCircuitBoard', 'other',
                'job', 'supplier', 'requestedUser', 'orderedUser', 'unregisteredModels'])->get();
        return view('order.orderedLists.index', compact('orderedLists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = $this->orderedListService->types;
        return view('order.orderedLists.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderedListRequest $request)
    {
        $orderedList = $this->orderedListService->store($request);
        $this->unregisteredModelService->confirm($orderedList, $request);
        $this->redisService->clear('all_ordered_lists_with_relations');
        return redirect()->route('orderedLists.index')->with('success', '発注済み依頼を作成しました');
    }

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
        $orderedList = OrderedList::with(['thing', 'part', 'printCircuitBoard', 'other',
            'job', 'supplier', 'unregisteredModels'])->get()->find($id);
        $orderedList->model_no = $orderedList->thing? $orderedList->thing->model_no :
            ($orderedList->unregisteredModelNo()? $orderedList->unregisteredModelNo()->name : null);
        $orderedList->supplierName = $orderedList->supplier? $orderedList->supplier->name :
            ($orderedList->unregisteredSupplier()? $orderedList->unregisteredSupplier()->name : null);
        return view('order.orderedLists.edit', compact('orderedList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderedListRequest $request, OrderedList $orderedList)
    {
        try {
            $this->unregisteredModelService->confirm($orderedList, $request);
            $orderedList = $this->orderedListService->update($request, $orderedList);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        $this->redisService->clear('all_ordered_lists_with_relations');
        return redirect()->route('orderedLists.index')->with('success', '変更を保存しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderedList = OrderedList::find($id);
        $orderedList->forceDelete();
        $this->redisService->clear('all_ordered_lists_with_relations');
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
                    $orderedList = OrderedList::find($id);
                    $this->unregisteredModelService->delete($orderedList, 'Supplier');
                    $this->destroy($id);
                }
            });
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        return redirect()->route('orderedLists.index')->with('danger', '削除が完了しました');
    }

    public function receive(OrderedList $orderedList)
    {
        $purchasedList = $this->purchasedListService->receive($orderedList);
        $this->unregisteredModelService->receive($orderedList, $purchasedList);
        $this->destroy($orderedList->id);
    }

    public function multiReceive(Request $request)
    {
        try{
            if (empty($request->ids)) {
                throw  new \Exception("何も選択されていません");
            }
            DB::transaction(function () use ($request) {
                foreach ($request->ids as $id) {
                    $orderedList = OrderedList::find($id);
                    if ($orderedList->unit_price === null || $orderedList->depository === null) {
                        throw new \Exception("受領には袋単価と棚が必須です");
                    }
                    $this->receive($orderedList);
                }
            });
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        return redirect()->route('orderedLists.index')->with('success', '受領手続きが完了しました');
    }

    public function import(Request $request)
    {
        try{
            $file = $request->file('file-ordered-list');
            $csvArray = $this->orderedListCsvImportService->uploadedCsvToArray($file);
            unset($csvArray[0]);

            foreach ($csvArray as $row) {
                [$orderedList, $row] = $this->orderedListService->import($row);
                $this->unregisteredModelService->confirm($orderedList, $row);
            }
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        $this->redisService->clear('all_ordered_lists_with_relations');
        return redirect()->route('orderedLists.index')->with('success', 'アップロードが完了しました');
    }
}
