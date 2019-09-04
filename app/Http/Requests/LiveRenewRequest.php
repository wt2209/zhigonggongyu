<?php

namespace App\Http\Requests;

use App\Models\Record;
use App\Rules\DateIn32BitComputer;
use App\Rules\RecordCanRenew;
use Illuminate\Foundation\Http\FormRequest;

class LiveRenewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $record = Record::find($this->route('id'));
        return $record && $record->type->has_contract;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_end_at' => ['bail', 'required', 'date', new RecordCanRenew($this->route('id'))],
            'new_contract_end' => ['required', new DateIn32BitComputer()],
        ];
    }

    public function messages()
    {
        return [
            'new_end_at.required' => '必须填写',
            'new_end_at.date' => '必须是一个日期格式，如：2018-9-3',
            'new_contract_end.required' => '必须填写',
        ];
    }
}
