<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Board\Thing;
use App\Models\Board\PrintCircuitBoard;
use App\Models\Board\MountedBoard;
use App\Models\Board\DetailedBoard;

class DetailedBoardsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'id', 'model_no', 'hardware_operation', 'adjustment_calibration',
        'shipment', 'hardware_version', 'software_version', 'check_sum',
        'created_at', 'lot_amount', 'user', 'depository', 'note',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvArray = [];
        try {
            $csvArray = Csv::uploadedCsvToArraySJIS(__DIR__ . '/seed/detailed_records_of_kibans.csv', $this->data);
        } catch (\Exception $exception) {
            throw $exception;
        }

        foreach ($csvArray as &$csv) {
            foreach($csv as &$value) {
                if (empty($value)) {
                    $value = null;
                }
            }
            $model_no = $csv['model_no'];
            echo $model_no."\n";
            $deprecatedMountedBoards[] = $model_no;
            try {
                $printCircuitBoard = Thing::where('model_no', $model_no)->first()->printCircuitBoard;
                $mountedBoard = new MountedBoard();
                $mountedBoard->print_circuit_board_id = $printCircuitBoard->id;
                $mountedBoard->version = array_count_values($deprecatedMountedBoards)[$model_no];
                $mountedBoard->created_at = $csv['created_at'];
                $mountedBoard->save();
            } catch (\Exception $exception) {
                echo($exception->getMessage()."\n");
            }
            // try {
            //     $maker_id =
            //     $thing = new Thing();
            //     $thing->type = 'PrintCircuitBoard';
            //     $thing->model_no = $csv['model_no'];
            //     $thing->maker_id = $maker_id;
            //     $thing->save();
            // } catch (\Exception $exception) {
            //     echo($exception->getMessage());
            // }
            // try {
            //     $printCircuitBoard = new PrintCircuitBoard();
            //     $job->number = $csv['number'];
            //     $job->name = $csv['name'];
            //     $job->requester_id = $csv['requester_id'];
            //     $job->delivery_destination_id = !empty($csv['delivery_destination_id'])? $csv['delivery_destination_id'] : null;
            //     $job->description = $csv['description'];
            //     $job->save();
            // } catch (\Exception $exception) {
            //     echo($exception->getMessage());
            // }
        }
    }
}
