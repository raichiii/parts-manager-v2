<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Address\Company;
use App\Models\Order\Job;
use App\Models\Board\Thing;

class PurchasedListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'job_id' => 'required',
            'model_no' => 'required',
            'supplier_id' => 'required_with:supplier',
            'lot_size' => 'required|numeric|max:10000',
            'lot_amount' => 'required|numeric|max:10000',
            'lot_unit_id' => 'required',
            'unit_price' => 'required|numeric|max:1000000',
            'depository' => 'required',
            'purchase_at' => 'nullable|date'
        ];
    }

    public function messages()
    {
        return [
            'job_id.required' => '未登録のJobNoです。',
            'model_no.required' => '生基板と部品に未登録の型番です',
            'supplier_id.required_with' => '登録されていない仕入れ先(会社)です',
            'lot_size.max' => '入り数は最大10000個までです',
            'lot_amount.max' => '袋数は最大10000個までです',
            'unit_price.required' => '袋単価は必須です',
            'depository.required' => '棚は必須です',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->input('job_no');
        $job = Job::where('number', $input)->first();
        $id = isset($job)? $job->id : null;
        $this->merge(['job_id' => $id]);

        if (!is_null($this->input('supplier'))) {
            $input = $this->input('supplier');
            $company = Company::where('name', $input)->first();
            $id = isset($company)? $company->id : 0; //未登録文字列の場合0でバリデーション通過
            $this->merge(['supplier_id' => $id]);
        }

        if (!is_null($this->input('model_no'))) {
            $input = $this->input('model_no');
            $thing = Thing::where('model_no', $input)->first();
            $id = isset($thing)? $thing->id : 0;
            $this->merge(['thing_id' => $id]);
        }
    }
}
