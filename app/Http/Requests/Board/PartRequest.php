<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Address\Company;
use Illuminate\Validation\Rule;

class PartRequest extends FormRequest
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
            'model_no' => ['required', 'string', 'max:127', Rule::unique('App\Models\Board\Thing')->ignore($this->part? $this->part->thing : null)],
            'maker_id' => 'required',
            'name' => 'required|string|max:127',
            'short_name' => 'nullable|string|max:63',
            'english_name' => 'nullable|string|max:127',
            'value' => 'nullable|string|max:63',
            'note' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'maker_id.required' => '登録されていないメーカーです',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['type' => 'Part']);

        $input = $this->input('maker');
        $maker = Company::where('name', $input)->first();
        $id = isset($maker)? $maker->id : null ;
        $this->merge(['maker_id' => $id]);
    }
}
