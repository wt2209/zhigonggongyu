<?php

namespace App\Http\Requests;

use App\Rules\RoomCanMoveTo;
use Illuminate\Foundation\Http\FormRequest;

class LiveMoveRequest extends FormRequest
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
            'move_to' => ['required', new RoomCanMoveTo($this->route('id'))],
        ];
    }

    public function messages()
    {
        return [
            'move_to.required' => '房间号必须填写',
        ];
    }
}
