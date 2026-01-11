<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:1000'],
            'post_id' => ['required', 'exists:posts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Comment text is required',
            'text.max' => 'Comment cannot be longer than 1000 characters',
        ];
    }
}
