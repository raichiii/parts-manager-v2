<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Address\Customer;
use App\Helpers;

class CustomersTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'id', 'furigana_family', 'furigana_first', 'family_name', 'first_name', 'office_id', 'department',
        'position', 'mobilephone_number', 'mobilephone_email', 'email1', 'email2', 'telephone_number', 'fax',
        'phone_number', 'recent_state', 'created_at', 'updated_at', 'note'
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
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/customer.csv', $this->data);
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

            if (!is_null($csv['mobilephone_email'])) {
                $emails[] = $csv['mobilephone_email'];
            }
            if (!is_null($csv['email1'])) {
                $emails[] = $csv['email1'];
            }
            if (!is_null($csv['email2'])) {
                $emails[] = $csv['email2'];
            }

            if (!is_null($csv['mobilephone_number'])) {
                $phones[] = $csv['mobilephone_number'];
            }
            if (!is_null($csv['telephone_number'])) {
                $phones[] = $csv['telephone_number'];
            }
            if (!is_null($csv['phone_number'])) {
                $phones[] = $csv['phone_number'];
            }
            if (!is_null($csv['fax'])) {
                $faxes[] = $csv['fax'];
            }

            try {
                $customer = new Customer();
                $customer->id = $csv['id'];
                $customer->office_id = $csv['office_id'];
                $customer->name = $csv['family_name']. $csv['first_name'];
                $customer->furigana = $csv['furigana_family']. $csv['furigana_first'];
                $customer->department = $csv['department'];
                $customer->position = $csv['position'];
                $customer->phones = $phones;
                $customer->faxes = $faxes;
                $customer->emails = $emails;
                $customer->note = $csv['note'];
                $customer->save();
            } catch(Exception $e){

            }
        }
    }
}
