<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CpCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'character_post_comment.body' => 'required|string|max:1000',
            'images' => 'array|max:4'
        ];
    }
}
