<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatesRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'rate' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'ad_category' => 'string',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'rate.required' => 'The ate field is required.',
            'ad_category' => 'The Ad Category field is required.',
        ];
    }
}
