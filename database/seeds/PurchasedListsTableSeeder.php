<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Traits\Csv;
use App\User;
use App\Models\Address\Company;
use App\Models\Order\PurchasedList;
use App\Models\Order\Job;
use Database\Seeds\SeederService;
use App\Services\Order\UnregisteredModelService;

class PurchasedListsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'job_no', 'model_no', 'maker_id', 'name', 'lot_amount', 'lot_unit', 'lot_size',
        'total_number', 'requested_user', 'requested_at', 'ordered_user', 'ordered_at', 'received_user', 'received_at',
        'supplier_id', 'depository', 'unit_price', 'total_price', 'purchase_at', 'merchandise_code', 'note', 'maker', 'supplier', 'phone', 'fax',
    ];

    public function __construct(SeederService $seederService, UnregisteredModelService $unregisteredModelService)
    {
        $this->seederService = $seederService;
        $this->unregisteredModelService = $unregisteredModelService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvArray = [];
        try {
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/purchased_list.csv', $this->data);
        } catch (\Exception $exception) {
            throw $exception;
        }
        unset($csvArray[0]);

        $csvArray = array_slice($csvArray, 0, 300); //消す

        foreach ($csvArray as &$csv) {
            foreach($csv as &$value) {
                if (empty($value)) {
                    $value = null;
                }
            }
            $model_no = $csv['model_no'];
            $job_no = substr($csv['job_no'], 0, 6);
            $lot_size = $csv['lot_size']? $csv['lot_size'] : 1;
            $lot_amount = $csv['lot_amount']? $csv['lot_amount'] : 1;
            $unit_price = $csv['unit_price']? $csv['unit_price'] : 0;
            $requested_user = $this->seederService->repair_user($csv['requested_user']);
            $ordered_user = $this->seederService->repair_user($csv['ordered_user']);
            $received_user = $this->seederService->repair_user($csv['received_user']);
            $requested_user_id = $this->seederService->search_user($requested_user);
            $ordered_user_id = $this->seederService->search_user($ordered_user);
            $received_user_id = $this->seederService->search_user($received_user);

            $thing_id = $this->seederService->search_model_no($csv['model_no']);

            $job_id = $this->seederService->search_job($job_no);

            $supplier_id = $this->seederService->search_supplier($csv['supplier'], $csv['supplier_id']);

            $lot_unit_id = $this->seederService->search_lot_unit($csv['lot_unit']);

            $purchasedList = new PurchasedList();
            $purchasedList->thing_id = $thing_id;
            $purchasedList->job_id = $job_id;
            $purchasedList->lot_size = $lot_size;
            $purchasedList->lot_amount = $lot_amount;
            $purchasedList->lot_unit_id = $lot_unit_id;
            $purchasedList->supplier_id = $supplier_id;
            try{
                $purchasedList->purchase_at = $csv['purchase_at'];
            }catch(\Exception $exception){
                //
            }
            $purchasedList->unit_price = $unit_price;
            $purchasedList->depository = $csv['depository'];
            $purchasedList->requested_user_id = $requested_user_id;
            $purchasedList->requested_at = $csv['requested_at'];
            $purchasedList->ordered_user_id = $ordered_user_id;
            $purchasedList->ordered_at = $csv['ordered_at'];
            $purchasedList->received_user_id = $received_user_id;
            if($csv['received_at'] !== '0000-00-00'){
                $purchasedList->created_at = $csv['received_at'];
            }else{
                $purchasedList->created_at = $csv['ordered_at'];
            }
            $purchasedList->save();

            $this->seederService->create_unregistered_model($purchasedList, $csv['model_no'], $csv['supplier']);

        }

        PurchasedList::factory()->count(10)->create();
    }
}
