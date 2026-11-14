<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\RequestedListRequest;
use App\Listeners\LineEventSubscriber;
use App\Models\Order\RequestedList;
use App\Services\Order\RequestedListService;
use App\Services\Order\OrderedListService;
use App\Services\Order\UnregisteredModelService;
use App\Services\Csv\RequestedListCsvImportService;
use App\Services\RedisService;
use Illuminate\Database\Eloquent\Collection;

class RequestedListController extends Controller
{
    private $requestedListService;
    private $orderedListService;
    private $requestedListCsvImportService;
    private $unregisteredModelService;
    private $redisService;
    private $lineEventSubscriber;

    public function __construct(RequestedListService $requestedListService,
        OrderedListService $orderedListService,
        RequestedListCsvImportService $requestedListCsvImportService,
        UnregisteredModelService $unregisteredModelService,
        RedisService $redisService,
        LineEventSubscriber $lineEventSubscriber)
    {
        $this->requestedListService = $requestedListService;
        $this->orderedListService = $orderedListService;
        $this->requestedListCsvImportService = $requestedListCsvImportService;
        $this->unregisteredModelService = $unregisteredModelService;
        $this->redisService = $redisService;
        $this->lineEventSubscriber = $lineEventSubscriber;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $requestedLists = $this->redisService->get_and_set('all_requested_lists_with_relations',
        //     function () {
        //         return RequestedList::with(['thing', 'part', 'printCircuitBoard', 'other',
        //             'job', 'supplier', 'requestedUser', 'unregisteredModels'])->get();
        //     },
        //     function ($result) {
        //         return new Collection($result);
        //     }
        // );
        $requestedLists = RequestedList::with(['thing', 'part', 'printCircuitBoard', 'other',
            'job', 'supplier', 'requestedUser', 'unregisteredModels'])->get();
        return view('order.requestedLists.index', compact('requestedLists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('order.requestedLists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestedListRequest $request)
    {
        $requestedList = $this->requestedListService->store($request);
        $this->unregisteredModelService->confirm($requestedList, $request);
        $this->lineEventSubscriber->handleOrderCreated($requestedList);
        $this->redisService->clear('all_requested_lists_with_relations');
        return redirect()->route('requestedLists.index')->with('success', '購入依頼を作成しました');
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
        $requestedList = RequestedList::with(['thing', 'part', 'printCircuitBoard', 'other',
            'job', 'supplier', 'unregisteredModels'])->get()->find($id);
        $requestedList->model_no = $requestedList->thing? $requestedList->thing->model_no :
            ($requestedList->unregisteredModelNo()? $requestedList->unregisteredModelNo()->name : null);
            $requestedList->supplierName = $requestedList->supplier? $requestedList->supplier->name :
            ($requestedList->unregisteredSupplier()? $requestedList->unregisteredSupplier()->name : null);
        return view('order.requestedLists.edit', compact('requestedList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestedListRequest $request, RequestedList $requestedList)
    {
        try {
            $requestedList = $this->requestedListService->update($request, $requestedList);
            $this->unregisteredModelService->confirm($requestedList, $request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        $this->redisService->clear('all_requested_lists_with_relations');
        return redirect()->route('requestedLists.index')->with('success', '変更を保存しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requestedList = RequestedList::find($id);
        $requestedList->forceDelete();
        $this->redisService->clear('all_requested_lists_with_relations');
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
                    $requestedList = RequestedList::find($id);
                    $this->unregisteredModelService->list_delete($requestedList);
                    $this->destroy($id);
                }
            });
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        return redirect()->route('requestedLists.index')->with('danger', '削除が完了しました');
    }

    public function order(RequestedList $requestedList)
    {
        $orderedList = $this->orderedListService->order($requestedList);
        $this->unregisteredModelService->order($requestedList, $orderedList);
        $this->destroy($requestedList->id);
    }

    public function multiOrder(Request $request)
    {
        try{
            if (empty($request->ids)) {
                throw  new \Exception("何も選択されていません");
            }
            DB::transaction(function () use ($request) {
                foreach ($request->ids as $id) {
                    $requestedList = RequestedList::find($id);
                    if ($requestedList->supplier === null && !$requestedList->unregisteredModels->where('type', 'Supplier')->first()) {
                        throw new \Exception("発注には仕入れ先が必須です");
                    }
                    $this->order($requestedList);
                }
            });
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        return redirect()->route('requestedLists.index')->with('success', '発注手続きが完了しました');
    }

    /**
     * Replecate existing order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function replicate(RequestedListRequest $request)
    {
        $requestedList = $this->requestedListService->store($request);
        $this->unregisteredModelService->confirm($requestedList, $request);
        return redirect()->route('requestedLists.index')->with('success', '購入依頼を複製しました');
    }

    public function import(Request $request)
    {
        try{
            $file = $request->file('file-requested-list');
            $csvArray = $this->requestedListCsvImportService->uploadedCsvToArray($file);
            unset($csvArray[0]);

            foreach ($csvArray as $row) {
                [$requestedList, $row] = $this->requestedListService->import($row);
                $this->unregisteredModelService->confirm($requestedList, $row);
            }
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
        $this->redisService->clear('all_requested_lists_with_relations');
        return redirect()->route('requestedLists.index')->with('success', 'アップロードが完了しました');
    }
}
