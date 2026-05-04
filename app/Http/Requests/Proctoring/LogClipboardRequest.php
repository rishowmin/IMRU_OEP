<?php

namespace App\Http\Requests\Proctoring;

use Illuminate\Foundation\Http\FormRequest;

class LogClipboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->route('attempt')->student_id === auth('student')->id();
        return (int) $this->route('attempt')->student_id === (int) auth()->id();
    }

    public function rules(): array
    {
        return [
            'action_type' => ['required', 'string', 'in:copy,paste,cut'],
        ];
    }

    public function messages(): array
    {
        return [
            'action_type.in' => 'Invalid clipboard action. Must be copy, paste, or cut.',
        ];
    }
}
