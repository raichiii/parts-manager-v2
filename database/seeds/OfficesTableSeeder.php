<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Address\Office;

class OfficesTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'id', 'company_id', 'name', 'postalcode_front', 'postalcode_back', 'prefecture_id', 'address',
        'house_number_1', 'house_number_2', 'house_number_3', 'building', 'phone', 'fax',
        'email', 'created_at', 'updated_at', 'note'
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
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/office.csv', $this->data);
        } catch (\Exception $exception) {
            throw $exception;
        }

        foreach ($csvArray as &$csv) {
            foreach($csv as &$value) {
                if (empty($value)) {
                    $value = null;
                }
            }
            $phones = [];
            $faxes = [];
            $emails = [];
            $address = "";
            $postalcode = null;

            if (!is_null($csv['email'])) {
                $emails[] = $csv['email'];
            }
            if (!is_null($csv['phone'])) {
                $phones[] = $csv['phone'];
            }
            if (!is_null($csv['fax'])) {
                $faxes[] = $csv['fax'];
            }

            if ($csv['address'] != '') {
                $address = $csv['address'];
            }
            if ($csv['house_number_1'] != '') {
                $address = $address . $csv['house_number_1'];
            }
            if ($csv['house_number_2'] != '') {
                if ($address != '' && $csv['house_number_1'] != ''){
                    $address = $address . '-' . $csv['house_number_2'];
                } else {
                    $address = $address . $csv['house_number_2'];
                }
            }
            if ($csv['house_number_3'] != '') {
                if ($address != '' && ($csv['house_number_1'] != '' || $csv['house_number_2'] != '')){
                    $address = $address . '-' . $csv['house_number_3'];
                } else {
                    $address = $address . $csv['house_number_3'];
                }
            }
            if (is_null($address)) {
                $address = null;
            }

            if ($csv['prefecture_id'] == 0) {
                $csv['prefecture_id'] = null;
            }

            if (!empty($csv['postalcode_front']) && !empty($csv['postalcode_back'])) {
                $postalcode = $csv['postalcode_front'] . '-'. $csv['postalcode_back'];
            }

            try {
                $office = new Office();
                $office->id = $csv['id'];
                $office->company_id = $csv['company_id'];
                $office->name = $csv['name'];
                $office->postalcode = $postalcode;
                $office->prefecture_id = $csv['prefecture_id'];
                $office->address = $address;
                $office->building = $csv['building'];
                $office->phones = $phones;
                $office->faxes = $faxes;
                $office->emails = $emails;
                $office->note = $csv['note'];
                $office->save();
            } catch(Exception $e){

            }
        }

        Office::factory()->count(200)->create();
    }
}
