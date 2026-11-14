<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Address\Company;
use Database\Seeds\SeederService;

class CompaniesTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'id', 'furigana', 'name', 'english_name', 'classification_id', 'old_name',
        'reason', 'short_name', 'short_english_name', 'url1', 'url2', 'created_at', 'updated_at', 'note'
    ];


    public function __construct(SeederService $seederService)
    {
        $this->seederService = $seederService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // App\Models\Address\Company::factory(30)->create();
        $csvArray = [];
        try {
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/company.csv', $this->data);
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
            $urls = [];
            $type = null;
            $type_side = null;
            $stringPoint = 0;

            if ($csv['url1'] != "") {
                $urls[] = $csv['url1'];
            }
            if ($csv['url2'] != "") {
                $urls[] = $csv['url2'];
            }
            $stringPoint = (mb_strpos($csv['name'], '㈱') === false) ? mb_strpos($csv['name'], '株式会社') : mb_strpos($csv['name'], '㈱');
            if ($stringPoint === 0) {
                $type = CMP_TYPE_KABUSHIKI;
                $type_side = CMP_TYPE_SIDE_MAE;
                $replace_table = [
                    '㈱' => '',
                    '株式会社' => '',
                ];
                $csv['name'] = str_replace(array_keys($replace_table), array_values($replace_table), $csv['name']);
            }
            else if($stringPoint){
                $type = CMP_TYPE_KABUSHIKI;
                $type_side = CMP_TYPE_SIDE_ATO;
                $replace_table = [
                    '㈱' => '',
                    '株式会社' => '',
                ];
                $csv['name'] = str_replace(array_keys($replace_table), array_values($replace_table), $csv['name']);
            }
            else{
                $stringPoint = (mb_strpos($csv['name'], '㈲') === false) ? mb_strpos($csv['name'], '有限会社') : mb_strpos($csv['name'], '㈲');
                if ($stringPoint === 0) {
                    $type = CMP_TYPE_YUUGEN;
                    $type_side = CMP_TYPE_SIDE_MAE;
                    $replace_table = [
                        '㈲' => '',
                        '有限会社' => '',
                    ];
                    $csv['name'] = str_replace(array_keys($replace_table), array_values($replace_table), $csv['name']);

                }
                else if($stringPoint) {
                    $type = CMP_TYPE_YUUGEN;
                    $type_side = CMP_TYPE_SIDE_ATO;
                    $replace_table = [
                        '㈲' => '',
                        '有限会社' => '',
                    ];
                    $csv['name'] = str_replace(array_keys($replace_table), array_values($replace_table), $csv['name']);

                }
                else {
                    $stringPoint = mb_strpos($csv['name'], '合資会社');
                    if ($stringPoint === 0){
                        $type = CMP_TYPE_GOUSHI;
                        $type_side = CMP_TYPE_SIDE_MAE;
                        $csv['name'] = str_replace('合資会社', '', $csv['name']);
                    }
                    else if($stringPoint) {
                        $type = CMP_TYPE_GOUSHI;
                        $type_side = CMP_TYPE_SIDE_ATO;
                        $csv['name'] = str_replace('合資会社', '', $csv['name']);
                    }
                    else {
                        $stringPoint = mb_strpos($csv['name'], '合同会社');
                        if ($stringPoint === 0){
                            $type = CMP_TYPE_GOUDOU;
                            $type_side = CMP_TYPE_SIDE_MAE;
                            $csv['name'] = str_replace('合同会社', '', $csv['name']);
                        }
                        else if($stringPoint) {
                            $type = CMP_TYPE_GOUDOU;
                            $type_side = CMP_TYPE_SIDE_ATO;
                            $csv['name'] = str_replace('合同会社', '', $csv['name']);
                        }
                        else {
                            $stringPoint = mb_strpos($csv['name'], '合名会社');
                            if ($stringPoint === 0){
                                $type = CMP_TYPE_GOUMEI;
                                $type_side = CMP_TYPE_SIDE_MAE;
                                $csv['name'] = str_replace('合名会社', '', $csv['name']);
                            }
                            else if($stringPoint) {
                                $type = CMP_TYPE_GOUMEI;
                                $type_side = CMP_TYPE_SIDE_ATO;
                                $csv['name'] = str_replace('合名会社', '', $csv['name']);
                            }
                        }
                    }
                }
            }
            $created_at = $csv['created_at'] === '0000-00-00'? '2007/1/1' : $csv['created_at'];
            $updated_at = $csv['updated_at'] === '0000-00-00'? $created_at : $csv['updated_at'];

            $company = new Company();
            $company->id = $csv['id'];
            $company->name = $csv['name'];
            $company->furigana = $csv['furigana'];
            $company->english_name = $csv['english_name'];
            $company->short_name = $csv['short_name'];
            $company->short_english_name = $csv['short_english_name'];
            $company->type = $type;
            $company->type_side = $type_side;
            $company->urls = $urls;
            $company->note = $csv['note'];
            $company->created_at = $created_at;
            $company->updated_at = $updated_at;
            try{
                $company->save();
            }catch(\Exception $exception){
                while(true){
                    try{
                        echo $csv['id']." Duplicated company: ".$company->name."\n";
                        $company->name = $company->name.'_';
                        $company->save();
                        break;
                    }catch(\Exception $exception){
                        //
                    }
                }
            }

            if($csv['classification_id'] !== null){
                $old_classification_id = log($csv['classification_id'], 2) + 1;
                $classification_id = $this->seederService->repair_classification_id($old_classification_id);
                $company->classifications()->sync($classification_id);
            }
        }
    }
}
