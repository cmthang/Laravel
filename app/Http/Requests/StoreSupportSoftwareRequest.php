<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportSoftwareRequest extends FormRequest
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
            'software' => 'required',
            'lable' => 'required',
            'value' => 'required',
            'order_version' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'software.required' => 'This field software is required',
            'lable.required' => 'This field lable is required',
            'value.required' => 'This field value is required',
            'order_version.required' => 'This order_version field is required',
        ];
    }
}
