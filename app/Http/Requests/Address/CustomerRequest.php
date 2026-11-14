<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'office_id' => 'required|exists:offices,id',
            'furigana' => 'nullable|string|max:63',
            'english_name' => 'nullable|string|max:127',
            'department' => 'nullable|string|max:63',
            'position' => 'nullable|string|max:63',
            'phones.*' => 'nullable|string|max:15',
            'faxes.*' => 'nullable|string|max:15',
            'emails.*' => 'nullable|email',
            'note' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'office_id.exists' => '登録されていない事務所です',
        ];
    }
}
