<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    public function rules()
    {
        return [
            'bill_no' => 'required',
            'bill_date' => 'required',
            'ad_id' => 'required',
            'paid_by' =>'required'
        ];

    }

    public function messages()
    {
        return [
            'bill_no.required' => 'The Bill No is required.',
            'bill_date.required' => 'The Bill Date is required.',
            'ad_id.required' => 'The Advertisement No is required.',
            'paid_by.required' => 'The Payment Head is required.',
        ];
    }
}
