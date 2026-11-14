<?php

namespace App\Services\Csv;

use App\Models\Board\Part;
use App\Traits\Csv;

class CsvImportPartService
{
    use Csv;

    private $data = ['reference_number', 'model_no', 'is_mounted'];

    /**
     * csvファイルからデータを抽出し、配列で返す
     * @param mixed $file
     * @return array
     * @throws \Exception
     */
    public function uploadedCsvToArray($file)
    {
        try {
            return Csv::uploadedCsvToArray($file, $this->data);
        } catch (\Exception $exception) {
            throw new \Exception("CSVデータに問題があります");
        }
    }

    /**
     * csvの配列の型番を登録されているか確認し、リストを返すメソッド
     * @param mixed $csvArray
     * @return array
     */
    public function getModelNumbers($csvArray)
    {
        $results = [];
        foreach ($csvArray as $data) {
            $modelNumbersList[] = $data['model_no'];
        }
        // ユニークな型番の一覧を取得
        $modelNumbersList = array_merge(array_unique($modelNumbersList));
        foreach ($modelNumbersList as $modelNumber) {
            //部品テーブルに登録済かを型番で確認する
            $part = Part::where('model_no', $modelNumber)->first();
            // 存在しないなら未登録リストに型番を記録、存在するなら登録済リストに(part.model_no => part.id)の形で登録
            if (is_null($part)) {
                $results['unregistered'][] = array($modelNumber);
            } else {
                $results['registered'][$modelNumber] = $part->id;
            }
        }
        return $results;
    }

    /**
     * csvデータのvalidation
     */
    public function validationRules()
    {
        return [
            'reference_number' => 'nullable|string',
            'is_mounted' => 'nullable|boolean',
        ];
    }

    /**
     * validationのエラーメッセージ
     */
    public function validationMessages()
    {
        return [];
    }
}
