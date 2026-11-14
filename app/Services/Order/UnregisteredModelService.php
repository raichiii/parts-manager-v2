<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\DB;
use App\Models\Order\RequestedList;
use App\Models\Order\OrderedList;
use App\Models\Order\PurchasedList;
use App\Models\Order\UnregisteredModel;

class UnregisteredModelService
{
    public function __construct()
    {
        $this->types = ['ModelNo', 'Supplier'];
        $this->model_columns = [
            'ModelNo' => 'model_no',
            'Supplier' => 'name',
        ];
        $this->type_columns = [
            'ModelNo' => 'model_no',
            'Supplier' => 'supplier',
        ];
        $this->type_ids = [
            'ModelNo' => 'thing_id',
            'Supplier' => 'supplier_id',
        ];
    }
    public function create($listable, $name, $type)
    {
        $listable_id = $listable->id;
        $listable_type = get_class($listable);
        $unregisteredModel = UnregisteredModel::create([
            'listable_id' => $listable_id,
            'listable_type' => $listable_type,
            'name' => $name,
            'type' => $type,
        ]);
        return $unregisteredModel;
    }

    public function update($model, $type)
    {
        $name = $model[$this->model_columns[$type]];
        $type_id = $model->id;
        $unregisteredModels = UnregisteredModel::where('name', $name)->where('type', $type)->get();
        foreach($unregisteredModels as $unregisteredModel) {
            $listable_id = $unregisteredModel->listable_id;
            $listable_type = $unregisteredModel->listable_type;
            $listable = new $listable_type; # クラス名の文字列からクラスのインスタンスを生成
            $listable = $listable->find($listable_id);
            $listable[$this->type_ids[$type]] = $type_id;
            $listable->save();
            $unregisteredModel->forceDelete();
        }
    }

    public function delete($listable, $type)
    {
        $listable_id = $listable->id;
        $listable_type = get_class($listable);
        $unregisteredModel = UnregisteredModel::where('listable_id', $listable_id)
            ->where('listable_type', $listable_type)->where('type', $type)->first();
        if(!is_null($unregisteredModel)) {
            $unregisteredModel->forceDelete();
        }
    }

    public function list_delete($listable)
    {
        foreach($this->types as $type) {
            $this->delete($listable, $type);
        }
    }

    public function confirm($listable, $request)
    {
        foreach($this->types as $type) {
            if($listable[$this->type_ids[$type]]) {
                $this->delete($listable, $type);
            }else{
                $listable_id = $listable->id;
                $listable_type = get_class($listable);
                $name = $request[$this->type_columns[$type]];
                if(is_null($name)){
                    $this->delete($listable, $type);
                    continue;
                }
                $unregisteredModel = UnregisteredModel::where('listable_id', $listable_id)
                    ->where('listable_type', $listable_type)->where('type', $type)->first();
                if(is_null($unregisteredModel)) {
                    $unregisteredModel = $this->create($listable, $name, $type);
                }else{
                    $unregisteredModel->name = $name;
                    $unregisteredModel->save();
                }
            }
        }
    }

    public function order(RequestedList $requestedList, OrderedList $orderedList)
    {
        $unregisteredModels = UnregisteredModel::where('listable_id', $requestedList->id)
            ->where('listable_type', 'App\Models\Order\RequestedList')->get();

        foreach($unregisteredModels as $unregisteredModel) {
            $unregisteredModel->listable_id = $orderedList->id;
            $unregisteredModel->listable_type = OrderedList::class;
            $unregisteredModel->save();
        }
    }

    public function receive(OrderedList $orderedList, PurchasedList $purchasedList)
    {
        $unregisteredModels = UnregisteredModel::where('listable_id', $orderedList->id)
            ->where('listable_type', OrderedList::class)->get();

        foreach($unregisteredModels as $unregisteredModel) {
            $unregisteredModel->listable_id = $purchasedList->id;
            $unregisteredModel->listable_type = PurchasedList::class;
            $unregisteredModel->save();
        }
    }
}
