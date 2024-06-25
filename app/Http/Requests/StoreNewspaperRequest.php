<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewspaperRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'news_type_id' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'news_type_id.required' => 'The newsppaer type field is required.',
        ];
    }
}
