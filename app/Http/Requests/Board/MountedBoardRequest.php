<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Board\MountedBoard;

class MountedBoardRequest extends FormRequest
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
            'print_circuit_board_id' => 'required|exists:print_circuit_boards,id',
            'version' => 'required|integer',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'print_circuit_board_id.exists' => '登録されていない生基板です',
        ];
    }

    protected function prepareForValidation()
    {
        //
    }
}
