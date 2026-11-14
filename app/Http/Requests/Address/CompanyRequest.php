<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'furigana' => 'nullable|string|max:255',
            'english_name' => 'nullable|string|max:255',
            'urls.*' => 'nullable|url',
            'short_name' => 'nullable|string|max:63',
            'note' => 'nullable|string',
        ];
    }
}
