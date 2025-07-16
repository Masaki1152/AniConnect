<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkStoryPostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'work_story_post.post_title' => 'required|string|max:40',
            'work_story_post.body' => 'required|string|max:4000',
            'work_story_post.categories_array' => 'required|array|max:3',
            'images' => 'array|max:4'
        ];
    }
}
