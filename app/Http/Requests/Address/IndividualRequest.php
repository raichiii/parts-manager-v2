<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class IndividualRequest extends FormRequest
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
            'name' => 'required|string|max:63',
            'furigana' => 'nullable|string|max:63',
            'english_name' => 'nullable|string|max:127',
            'postalcode' => 'required|string|max:8',
            'prefecture_id' => 'required|exists:prefectures,id',
            'address' => 'required|string|max:127',
            'phones.*' => 'nullable|string|max:15',
            'faxes.*' => 'nullable|string|max:15',
            'emails.*' => 'nullable|email',
            'urls.*' => 'nullable|url',
            'note' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'prefecture_id.exists' => '登録されていない都道府県です',
        ];
    }
}
