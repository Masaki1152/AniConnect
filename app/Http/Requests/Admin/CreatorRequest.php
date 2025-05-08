<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreatorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'creators.name' => 'required|string|max:200',
            'creators.wiki_link' => 'required|string|max:200'
        ];
    }
}
