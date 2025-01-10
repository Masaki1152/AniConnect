<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WspCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'work_story_post_comment.body' => 'required|string|max:200',
            'images' => 'array|max:4'
        ];
    }
}
