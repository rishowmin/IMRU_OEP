<?php

namespace App\Http\Requests\Proctoring;

use Illuminate\Foundation\Http\FormRequest;

class LogTabSwitchRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow if attempt belongs to logged-in student
        // return $this->route('attempt')->student_id === auth('student')->id();
        return (int) $this->route('attempt')->student_id === (int) auth()->id();
    }

    public function rules(): array
    {
        return [
            'returned_at' => ['nullable', 'date'],
            'duration_ms' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
