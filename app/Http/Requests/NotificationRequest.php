<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'notification.title' => 'required|string|max:100',
            'notification.body' => 'required|string|max:4000',
            //'notification.categories_array' => 'required|array|max:3',
            'images' => 'array|max:4'
        ];
    }
}
