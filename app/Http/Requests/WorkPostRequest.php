<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'work_post.post_title' => 'required|string|max:40',
            'work_post.body' => 'required|string|max:4000',
            'work_post.categories_array' => 'required|array|max:3',
            'images' => 'array|max:4'
        ];
    }
}
