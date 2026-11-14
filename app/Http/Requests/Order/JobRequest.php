<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Address\Company;
use Illuminate\Validation\Rule;

class JobRequest extends FormRequest
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
            'number' => ['required', 'string', 'max:6', Rule::unique('App\Models\Order\Job')->ignore($this->job)],
            'name' => 'required|string|max:127',
            'requester_id' => 'required',
            'delivery_destination_id' => 'required_with:delivery_destination',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'number.max' => 'JobNoは6文字以下で指定してください',
            'number.unique' => '既に登録されているJobNoです',
            'requester_id.required' => '登録されていない依頼元(会社)です',
            'delivery_destination_id.required_with' => '登録されていない納入先(会社)です',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->input('requester');
        $company = Company::where('name', $input)->first();
        $id = $company? $company->id : null;
        $this->merge(['requester_id' => $id]);

        if ($this->has('delivery_destination')) {
            $input = $this->input('delivery_destination');
            $company = Company::where('name', $input)->first();
            $id = $company? $company->id : null;
            $this->merge(['delivery_destination_id' => $id]);
        }
    }
}
