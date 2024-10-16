<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkReviewRequest extends FormRequest
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
            'work_review.work_id' => 'required|integer',
            'work_review.user_id' => 'required|integer',
            'work_review.post_title' => 'required|string|max:100',
            'work_review.body' => 'required|string|max:4000',
        ];
    }
}
