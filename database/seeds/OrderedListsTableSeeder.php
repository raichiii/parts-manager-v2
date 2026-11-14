<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Traits\Csv;
use App\User;
use App\Models\Address\Company;
use App\Models\Order\OrderedList;
use App\Models\Order\Job;
use Database\Seeds\SeederService;
use App\Services\Order\UnregisteredModelService;

class OrderedListsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'job_no', 'model_no', 'maker_id', 'name', 'lot_amount', 'lot_unit', 'lot_size',
        'total_number', 'requested_user', 'requested_at', 'ordered_user', 'ordered_at',
        'supplier_id', 'purchase_at', 'merchandise_code', 'note', 'maker', 'supplier', 'phone', 'fax',
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
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/ordered_list.csv', $this->data);
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
            $requested_user = $this->seederService->repair_user($csv['requested_user']);
            $ordered_user = $this->seederService->repair_user($csv['ordered_user']);
            $requested_user_id = $this->seederService->search_user($requested_user);
            $ordered_user_id = $this->seederService->search_user($ordered_user);

            $thing_id = $this->seederService->search_model_no($csv['model_no']);

            $job_id = $this->seederService->search_job($job_no);

            $supplier_id = $this->seederService->search_supplier($csv['supplier'], $csv['supplier_id']);

            $lot_unit_id = $this->seederService->search_lot_unit($csv['lot_unit']);

            $orderedList = new OrderedList();
            $orderedList->thing_id = $thing_id;
            $orderedList->job_id = $job_id;
            $orderedList->lot_size = $lot_size;
            $orderedList->lot_amount = $lot_amount;
            $orderedList->lot_unit_id = $lot_unit_id;
            $orderedList->supplier_id = $supplier_id;
            try{
                $orderedList->purchase_at = $csv['purchase_at'];
            }catch (\Exception $exception) {
                //
            }
            $orderedList->requested_user_id = $requested_user_id;
            $orderedList->requested_at = $csv['requested_at'];
            $orderedList->ordered_user_id = $ordered_user_id;
            $orderedList->created_at = $csv['ordered_at'];
            $orderedList->save();

            $this->seederService->create_unregistered_model($orderedList, $csv['model_no'], $csv['supplier']);
        }

        OrderedList::factory()->count(10)->create();
    }
}
