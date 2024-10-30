<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
{
    public function rules()
    {
        return [
            'advertisementType' => 'required|exists:advertisement_type,id',
            'issue_date' => 'required|date',
            'department' => 'required|string|max:255',
            'newspaper' => 'required|array',
            'newspaper.*' => 'exists:empanelled,id',
            'insertions' => 'required|integer|max:10',
            'ref_no' => 'required|string|max:255',
            'ref_date' => 'required|date',
            'subject' => 'nullable|exists:subject,id',
            'category' => 'nullable',
            'positively' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'color' => 'nullable',
            'page_info' => 'nullable',
            'payment_by' => 'required|alpha|size:1',
            'mipr_no' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'issue_date.required' => 'The Date of Issue field is required.',
            'issue_date.date' => 'The Date of Issue must be a valid date.',
            'department.required' => 'The Department field is required.',
            'department.max' => 'The Department may not be greater than :max characters.',
            'newspaper.required' => 'The Newspaper(s) field is required.',
            'newspaper.array' => 'The Newspaper(s) must be an array.',
            'newspaper.*.exists' => 'One or more selected Newspaper(s) are invalid.',
            'insertions.required' => 'The No of insertion field is required.',
            'insertions.max' => 'The No of insertion may not be greater than :max characters.',
            'ref_no.required' => 'The Reference No field is required.',
            'ref_no.max' => 'The Reference No may not be greater than :max characters.',
            'ref_date.required' => 'The Reference Date field is required.',
            'ref_date.date' => 'The Reference Date must be a valid date.',
            'subject.required' => 'The Subject field is required.',
            'subject.exists' => 'The selected Subject is invalid.',
            'category.required' => 'The Subject field is required.',
            'positively.required' => 'The Positively On ust be a valid date.',
            'remarks.string' => 'The Remarks must be a string.',
        ];
    }
}
