<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairMaterialRequest extends FormRequest
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
            '*.*.total' => 'required|numeric',
            '*.*.id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            '*.*.total.required' => '所有“用量”必须填写',
            '*.*.total.numeric' => '“用量”必须是一个数字',
            '*.*.id.required' => '非法请求',
            '*.*.id.integer' => '非法请求',
        ];
    }
}
