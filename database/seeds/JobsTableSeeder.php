<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Order\Job;

class JobsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'number', 'name', 'requester_id', 'delivery_destination_id', 'description',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Job::create([
            'number' => 'GP',
            'name' => 'General Purpose. 汎用目的',
            'requester_id' => 1,
            'delivery_destination_id' => 1,
        ]);

        $csvArray = [];
        try {
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/jobno.csv', $this->data);
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
            $job = new Job();
            $job->number = $csv['number'];
            $job->name = $csv['name'];
            $job->requester_id = $csv['requester_id'];
            $job->delivery_destination_id = !empty($csv['delivery_destination_id'])? $csv['delivery_destination_id'] : null;
            $job->description = $csv['description'];
            $job->save();
        }
    }
}
