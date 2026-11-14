<?php

namespace App\Services\Csv;

use App\Traits\Csv;

class OrderedListCsvImportService
{
    use Csv;

    private $data = ['model_no', 'job_no', 'supplier', 'lot_size', 'lot_amount',
        'lot_unit', 'unit_price', 'depository', 'purchase_at'];

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
