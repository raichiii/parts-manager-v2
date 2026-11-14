<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class OfficeRequest extends FormRequest
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
            'name' => 'required|string|max:127',
            'company_id' => 'required|exists:companies,id',
            'postalcode' => 'required|string|max:8',
            'prefecture_id' => 'required|exists:prefectures,id',
            'address' => 'required|string|max:127',
            'building' => 'nullable|string|max:63',
            'phones.*' => 'nullable|string|max:15',
            'faxes.*' => 'nullable|string|max:15',
            'emails.*' => 'nullable|email',
            'note' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'company_id.exists' => '登録されていない会社です',
            'prefecture_id.exists' => '登録されていない都道府県です',
        ];
    }
}
