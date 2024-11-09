<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharacterPostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'character_post.post_title' => 'required|string|max:100',
            'character_post.body' => 'required|string|max:4000',
            'character_post.categories_array' => 'required|array|max:3',
            'character_post.star_num' => 'required',
            'images' => 'array|max:4'
        ];
    }
}
