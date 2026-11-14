<?php

use Illuminate\Database\Seeder;
use App\Traits\Csv;
use App\Models\Address\Company;
use App\Models\Board\Thing;
use App\Models\Board\Part;

class PartsTableSeeder extends Seeder
{
    use Csv;

    private $data = [
        'model_no', 'stock', 'registered_at', 'pno', 'second', 'p',
        'common', 'name', 'classification', 'maker', 'status', 'interchange',
        'alternative', 'note', 'depository', 'unit_price', 'caution', 'o', 'arranging',
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
            $csvArray = Csv::uploadedCsvToArray(__DIR__ . '/seed/parts.csv', $this->data);
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

            $thing = Thing::where('model_no', $csv['model_no'])->first();
            if($thing){
                continue;
            }

            $maker = Company::where('name', $csv['maker'])->first();
            if(!$maker && $csv['maker']){
                $maker = Company::create([
                    'name' => $csv['maker'],
                    'short_name' => $csv['maker'].'(未入力)',
                    'urls' => [],
                ]);
                echo('New Company: '.$csv['maker']."\n");
            }
            $maker_id = $maker? $maker->id : null;
            if(!$maker_id){
                echo $csv['id'].' Undefined Maker: '.$csv['model_no']."\n";
                $maker_id = Company::create([
                    'name' => 'undefined_maker_for_'.$csv['model_no'],
                    'short_name' => 'undefined_'.$csv['model_no'].'(未入力)',
                    'urls' => [],
                ])->id;
            }

            $thing = new Thing();
            $thing->type = 'Part';
            $thing->model_no = $csv['model_no'];
            $thing->maker_id = $maker_id;
            $thing->created_at = $csv['registered_at'];
            $thing->save();

            $part = new Part();
            $part->thing_id = $thing->id;
            $part->name = $csv['name'];
            $part->pno = $csv['pno'];
            $part->p = $csv['p'];
            $part->common = $csv['common'];
            $part->classification = $csv['classification'];
            $part->status = $csv['status'];
            $part->interchange = $csv['interchange'];
            $part->alternative = $csv['alternative'];
            $part->note = $csv['note'];
            $part->created_at = $csv['registered_at'];
            $part->updated_at = $csv['registered_at'];
            $part->save();
        }
    }
}
