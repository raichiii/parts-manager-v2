<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Traits\Csv;
use App\User;
use App\Models\Order\RequestedList;
use App\Models\Order\OrderedList;
use App\Models\Order\UnregisteredModel;
use Database\Seeds\SeederService;
use App\Services\Order\UnregisteredModelService;

class RequestedListsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'job_no', 'model_no', 'maker_id', 'name', 'lot_amount', 'lot_unit', 'lot_size',
        'total_number', 'requested_user', 'requested_at', 'supplier_id', 'purchase_at',
        'merchandise_code', 'note', 'maker', 'supplier', 'ref_price',
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
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/requested_list.csv', $this->data);
        } catch (\Exception $exception) {
            throw $exception;
        }
        unset($csvArray[0]);

        foreach ($csvArray as &$csv) {
            foreach($csv as &$value) {
                if (empty($value)) {
                    $value = null;
                }
            }
            $job_no = substr($csv['job_no'], 0, 6);
            $lot_size = $csv['lot_size']? $csv['lot_size'] : 1;
            $lot_amount = $csv['lot_amount']? $csv['lot_amount'] : 1;

            $thing_id = $this->seederService->search_model_no($csv['model_no']);

            $job_id = $this->seederService->search_job($job_no);

            $supplier_id = $this->seederService->search_supplier($csv['supplier'], $csv['supplier_id']);

            $lot_unit_id = $this->seederService->search_lot_unit($csv['lot_unit']);

            $requestedList = new RequestedList();
            $requestedList->thing_id = $thing_id;
            $requestedList->job_id = $job_id;
            $requestedList->lot_size = $lot_size;
            $requestedList->lot_amount = $lot_amount;
            $requestedList->lot_unit_id = $lot_unit_id;
            $requestedList->supplier_id = $supplier_id;
            $requestedList->unit_price = $csv['ref_price'];
            try{
                $requestedList->purchase_at = $csv['purchase_at'];
            }catch (\Exception $exception) {
                //
            }
            $requested_user = $this->seederService->repair_user($csv['requested_user']);
            $requested_user = User::where('username', $requested_user)->first();
            $requestedList->requested_user_id = $requested_user->id;
            $requestedList->created_at = $csv['requested_at'];
            $requestedList->save();

            $this->seederService->create_unregistered_model($requestedList, $csv['model_no'], $csv['supplier']);
        }
    }
}
