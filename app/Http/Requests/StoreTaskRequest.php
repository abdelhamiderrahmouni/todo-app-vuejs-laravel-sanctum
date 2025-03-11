<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:500'],
            'is_completed' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The task name is required.',
            'name.string' => 'The task name must be a string.',
            'name.max' => 'The task name must not be greater than 255 characters.',
            'body.string' => 'The task body must be a string.',
            'body.max' => 'The task body must not be greater than 500 characters.',
            'is_completed.boolean' => 'The task completion status must be a boolean.',
        ];
    }
}
