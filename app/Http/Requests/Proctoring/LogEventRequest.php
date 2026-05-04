<?php

namespace App\Http\Requests\Proctoring;

use Illuminate\Foundation\Http\FormRequest;

class LogEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->route('attempt')->student_id === auth('student')->id();
        return (int) $this->route('attempt')->student_id === (int) auth()->id();
    }

    public function rules(): array
    {
        return [
            'event_type' => ['required', 'string', 'in:tab_switch,copy_attempt,paste_attempt,face_not_detected,multiple_faces,looking_away'],
            'severity'   => ['nullable', 'string', 'in:low,medium,high'],
            'metadata'   => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_type.required' => 'Event type is required.',
            'event_type.in'       => 'Invalid event type provided.',
            'severity.in'         => 'Severity must be low, medium, or high.',
        ];
    }
}
