<?php

namespace App\Http\Requests;

use App\Models\Person;
use App\Rules\DateIn32BitComputer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LiveUpdateRequest extends FormRequest
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
            'person.name' => 'required',
            'person.gender' => ['required', Rule::in(['男', '女'])],
            'person.education' => [Rule::in(array_keys(Person::$educationMap))],
            'person.entered_at' => 'nullable|date',
            'record_at' => 'nullable|date',
            'person.contract_start' => 'nullable|date|required_with:person.contract_end',
            'person.contract_end' => ['nullable', 'required_with:person.contract_start', new DateIn32BitComputer()],
        ];
    }

    public function messages()
    {
        return [
            'person.name.required' => '姓名不能为空',
            'person.gender.required' => '性别不能为空',
            'person.gender.in' => '非法操作',
            'person.education.in' => '非法操作',
            'person.entered_at.date' => '必须是一个日期，如：2018-8-31',
            'record_at.date' => '必须是一个日期，如：2018-8-31',
            'person.contract_start.date' => '必须是一个日期，如：2018-8-31',
            'person.contract_start.required_with' => '必须与劳动合同结束日同时存在',
            'person.contract_end.required_with' => '必须与劳动合同开始日同时存在',
        ];
    }
}
