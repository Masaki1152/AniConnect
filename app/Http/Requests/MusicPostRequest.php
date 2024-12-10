<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicPostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'music_post.post_title' => 'required|string|max:100',
            'music_post.body' => 'required|string|max:4000',
            'music_post.categories_array' => 'required|array|max:3',
            'music_post.star_num' => 'required',
        ];
    }
}
