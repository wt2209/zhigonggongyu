<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillImportRequest extends FormRequest
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
            'file' => 'required|mimes:xls,xlsx',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => '请选择一个文件',
            'file.mimes' => '必须是一个 .xls 或者 .xlsx 文件',
        ];
    }
}
