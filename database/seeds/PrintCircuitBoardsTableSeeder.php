<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Address\Company;
use App\Models\Board\Thing;
use App\Models\Board\Part;
use App\Models\Board\PrintCircuitBoard;
use Carbon\Carbon;


class PrintCircuitBoardsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'model_no', 'maker_id', 'valid_years',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvArray = [];
        $today = Carbon::today()->toDateString();
        try {
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/print_circuit_board.csv', $this->data);
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

            $maker_id = Company::find($csv['maker_id'])->id;

            $thing = new Thing();
            $thing->type = 'PrintCircuitBoard';
            $thing->model_no = $csv['model_no'];
            $thing->maker_id = $maker_id;
            $thing->save();

            $part = new PrintCircuitBoard();
            $part->thing_id = $thing->id;
            $part->valid_years = $csv['valid_years'];
            $part->save();
        }

        PrintCircuitBoard::factory()->count(30)->create();
    }
}
