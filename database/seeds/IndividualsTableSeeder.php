<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Address\Individual;

class IndividualsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'id', 'sei_furigana', 'mei_furigana', 'sei', 'mei', 'postalcode_front', 'postalcode_back',
        'prefecture_id', 'address', 'chome', 'banchi', 'ban', 'room_number', 'building', 'phone1',
        'email', 'fax', 'phone2', 'email2', 'email3', 'url1', 'url2', 'url3', 'note',
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
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/individual.csv', $this->data);
        } catch (\Exception $exception) {
            throw $exception;
        }
        $csvArray = array_slice($csvArray, 0, 3); //消す

        foreach ($csvArray as &$csv) {
            foreach($csv as &$value) {
                if (empty($value)) {
                    $value = null;
                }
            }

            $phones = [];
            $emails = [];
            $faxes = [];
            $urls = [];

            if (!empty($csv['phone1'])) {
                $phones[] = $csv['phone1'];
            }
            if (!empty($csv['phone2'])) {
                $phones[] = $csv['phone2'];
            }

            if (!empty($csv['url1'])) {
                $urls[] = $csv['url1'];
            }
            if (!empty($csv['url2'])) {
                $urls[] = $csv['url2'];
            }
            if (!empty($csv['url3'])) {
                $urls[] = $csv['url3'];
            }

            if (!empty($csv['fax'])) {
                $faxes[] = $csv['fax'];
            }

            if (!empty($csv['email1'])) {
                $emails[] = $csv['email1'];
            }
            if (!empty($csv['email2'])) {
                $emails[] = $csv['email2'];
            }
            if (!empty($csv['email3'])) {
                $emails[] = $csv['email3'];
            }

            $name = $csv['sei'].$csv['mei'];
            $furigana = $csv['sei_furigana'].$csv['mei_furigana'];
            $postalcode = $csv['postalcode_front'].$csv['postalcode_back'];
            $address = $csv['address'].$csv['chome'].'-'.$csv['banchi'].'-'.$csv['ban'].'-'.$csv['room_number'].', '.$csv['building'];

            $individual = new Individual();
            $individual->id = $csv['id'];
            $individual->name = $name;
            $individual->furigana = $furigana;
            // $individual->english_name = $english_name;
            $individual->postalcode = $postalcode;
            $individual->prefecture_id = $csv['prefecture_id'];
            $individual->address = $address;
            $individual->phones = $phones;
            $individual->faxes = $faxes;
            $individual->emails = $emails;
            $individual->urls = $urls;
            $individual->note = $csv['note'];

            $individual->save();
        }
    }
}
