<?php

namespace Database\Seeds;

use App\User;
use App\Models\Address\Company;
use App\models\Order\Job;
use App\Models\Order\LotUnit;
use App\Models\Order\UnregisteredModel;
use App\Models\Board\Thing;
use App\Models\Board\Part;
use App\Models\Board\PrintCircuitBoard;

class SeederService
{
    public function search_lot_unit($lot_unit)
    {
        try{
            $lot_unit_id = LotUnit::where('name', $lot_unit)->first()->id;
        }catch(\Exception $exception){
            if($lot_unit !== null){
                echo('No Lot Unit: '.$lot_unit."\n");
            }
            $lot_unit_id = LotUnit::where('name', '個')->first()->id;
        }
        return $lot_unit_id;
    }

    public function search_job($job_no)
    {
        $job_id = Job::where('number', $job_no)->first()->id;
        return $job_id;
    }

    public function search_model_no($model_no)
    {
        $thing = Thing::where('model_no', $model_no)->first();
        if($thing){
            return $thing->id;
        }else{
            return null;
        }
    }

    public function search_supplier($csv_supplier, $csv_supplier_id)
    {
        $supplier = Company::where('name', $csv_supplier)->first();
        if($supplier){
            $supplier_id = $supplier->id;
        }else{
            $supplier = Company::where('id', $csv_supplier_id)->first();
            if($supplier){
                $supplier_id = $supplier->id;
            }else{
                $supplier_id = null;
            }
        }
        return $supplier_id;
    }

    public function search_user($username)
    {
        try{
            $user_id = User::where('username', $username)->first()->id;
        }catch(\Exception $exception){
            $user_id = User::where('username', 'unknown')->first()->id;
            echo(' Unauthorized User: '.$username."\n");
        }
        return $user_id;
    }

    public function create_unregistered_model($listable, $model_no, $supplier)
    {
        if(!$listable->thing_id && $model_no){
            echo("Unregistered ModelNo: ".$model_no."\n");
            UnregisteredModel::create([
                'name' => $model_no,
                'type' => 'ModelNo',
                'listable_id' => $listable->id,
                'listable_type' => get_class($listable),
            ]);
        }
        if(!$listable->supplier_id && $supplier){
            echo("Unregistered Supplier: ".$supplier."\n");
            UnregisteredModel::create([
                'name' => $supplier,
                'type' => 'Supplier',
                'listable_id' => $listable->id,
                'listable_type' => get_class($listable),
            ]);
        }
    }

    public function repair_user($username)
    {
        switch($username){
            case 'Raichi':
                return 'raichi';
            case 'Cherii':
                return 'cherii';
        }
        return $username;
    }

    public function repair_classification_id($old_classification_id)
    {
        $old_jimu = 6;
        $new_jimu = 5;
        $old_kokancho = 8;
        $new_kokancho = 6;
        $old_other = 7;
        $new_other = 8;
        switch($old_classification_id){
            case $old_other:
                return $new_other;
            case $old_kokancho:
                return $new_kokancho;
            case $old_jimu:
                return $new_jimu;
        }
        return $old_classification_id;
    }
}
