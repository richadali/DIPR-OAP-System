<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'subject' => 'required|string|max:255',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'subject.required' => 'The advertisemnt type field is required.',
        ];
    }
}
