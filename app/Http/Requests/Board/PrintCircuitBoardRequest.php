<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Address\Company;
use Illuminate\Validation\Rule;

class PrintCircuitBoardRequest extends FormRequest
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
            'model_no' => ['required', 'string', 'max:127', Rule::unique('App\Models\Board\Thing')->ignore($this->print_circuit_board? $this->print_circuit_board->thing : null)],
            'maker_id' => 'required',
            'valid_years' => 'nullable|integer',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '既に登録されている生基板です',
            'maker_id.required' => '登録されていないメーカーです',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['type' => 'PrintCircuitBoard']);

        $input = $this->input('maker');
        $maker = Company::where('name', $input)->first();
        $id = isset($maker)? $maker->id : null ;
        $this->merge(['maker_id' => $id]);
    }
}
