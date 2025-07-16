<?php

namespace App\Http\Requests\Admin\RelatedParty;

use Illuminate\Foundation\Http\FormRequest;

class CreatorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'creators.name' => 'required|string|max:200',
            'creators.copyright' => 'max:200',
            'creators.official_site_link' => 'required|string|max:200',
            'creators.wiki_link' => 'required|string|max:200',
            'creators.twitter_link' => 'required|string|max:200'
        ];
    }
}
