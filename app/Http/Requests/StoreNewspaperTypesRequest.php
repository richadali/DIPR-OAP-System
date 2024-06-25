<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewspaperTypesRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'news_type' => 'required|string|max:255',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'news_type.required' => 'The newspaper type field is required.',
        ];
    }
}
