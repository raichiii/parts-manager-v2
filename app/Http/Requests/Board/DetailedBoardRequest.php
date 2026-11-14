<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Board\Thing;
use App\Models\Board\MountedBoard;

class DetailedBoardRequest extends FormRequest
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
            'mounted_board_id' => 'required',
            'lot_amount' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'mounted_board_id.required' => '登録されていない基板です',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('print_circuit_board') && $this->has('version')) {
            $input = $this->input('print_circuit_board');
            $printCircuitBoard = Thing::where('model_no', $input)->first()->printCircuitBoard;
            if (isset($printCircuitBoard)) {
                $input = $this->input('version');
                $mountedBoard = MountedBoard::where('print_circuit_board_id', $printCircuitBoard->id)->where('version', $input)->first();
                if (isset($mountedBoard)) {
                    $this->merge(['mounted_board_id' => $mountedBoard->id]);
                }
            }
        }
    }
}
