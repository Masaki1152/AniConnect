<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'works.creator_id' => 'required|string',
            'works.name' => 'required|string|max:200',
            'works.copyright' => 'max:200',
            'works.term' => 'required|string|max:200',
            'works.official_site_link' => 'required|string|max:200',
            'works.wiki_link' => 'required|string|max:200',
            'works.twitter_link' => 'required|string|max:200'
        ];
    }
}
