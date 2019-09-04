<?php

namespace App\Http\Requests;

use App\Models\BillType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BillRequest extends FormRequest
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
            'items.*.location' => 'required',
            'items.*.bill_type' => ['required', Rule::in(BillType::pluck('title')->toArray())],
            'items.*.cost' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'items.*.location.required' => '房间号/位置必须填写',
            'items.*.bill_type.required' => '费用类型必须填写',
            'items.*.bill_type.in' => '某个费用类型不存在',
            'items.*.cost.required' => '金额必须填写',
            'items.*.cost.numeric' => '金额必须是一个数字',
        ];
    }
}
