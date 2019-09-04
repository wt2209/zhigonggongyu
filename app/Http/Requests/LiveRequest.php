<?php

namespace App\Http\Requests;

use App\Models\Person;
use App\Rules\DateIn32BitComputer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LiveRequest extends FormRequest
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
            'room_id' => 'sometimes|required',
            'person.name' => 'required',
            'person.gender' => Rule::in(['男', '女']),
            'person.entered_at' => 'nullable|date',
            'person.contract_start' => 'nullable|date',
            'person.contract_end' => ['nullable', new DateIn32BitComputer()],
            'person.education' => Rule::in(array_keys(Person::$educationMap)),
            'start_at' => 'sometimes|required|date',
            'end_at' => 'sometimes|required|date',
        ];
    }

    public function messages()
    {
        return [
            'room_id.required' => '非法错误',
            'person.name.required' => '必须填写',
            'person.gender.in' => '非法错误',
            'person.entered_at.date' => '请填写一个日期格式，如：2018-9-4',
            'person.contract_start.date' => '请填写一个日期格式，如：2018-9-4',
            'person.education.in' => '非法错误',
            'start_at.required' => '必须填写',
            'start_at.date' => '请填写一个日期格式，如：2018-9-4',
            'end_at.required' => '必须填写',
            'end_at.date' => '请填写一个日期格式，如：2018-9-4',
        ];
    }
}
