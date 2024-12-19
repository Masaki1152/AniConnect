<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WrCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'work_review_comment.body' => 'required|string|max:1000',
            'images' => 'array|max:4'
        ];
    }
}
