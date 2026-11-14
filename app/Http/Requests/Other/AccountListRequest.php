<?php

namespace App\Http\Requests\Other;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Address\Company;

class AccountListRequest extends FormRequest
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
            'company_id' => 'required',
            'name' => 'required|string|max:127',
            'password' => 'required|string|max:127',
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => '登録されていない仕入れ先です',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('supplier')) {
            $input = $this->input('supplier');
            $company = Company::where('name', $input)->orWhere('furigana', $input)->orWhere('english_name', $input)
                ->orWhere('short_name', $input)->orWhere('short_english_name', $input)->first();
            if (isset($company) && $company->hasClassification(CLS_NAME_SUPPLIER)) {
                $this->merge(['company_id' => $company->id]);
            }
        }
    }
}
