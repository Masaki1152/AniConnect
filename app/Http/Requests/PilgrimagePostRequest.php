<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PilgrimagePostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pilgrimage_post.post_title' => 'required|string|max:40',
            'pilgrimage_post.scene' => 'required|string|max:40',
            'pilgrimage_post.body' => 'required|string|max:4000',
            'pilgrimage_post.categories_array' => 'required|array|max:3',
            'images' => 'array|max:4'
        ];
    }
}
