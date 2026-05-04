<?php

namespace App\Http\Requests\Proctoring;

use Illuminate\Foundation\Http\FormRequest;

class LogWebcamRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->route('attempt')->student_id === auth('student')->id();
        return (int) $this->route('attempt')->student_id === (int) auth()->id();
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Webcam snapshot is required.',
            'image.mimes'    => 'Image must be jpg, jpeg, or png.',
            'image.max'      => 'Image must not exceed 2MB.',
        ];
    }
}
