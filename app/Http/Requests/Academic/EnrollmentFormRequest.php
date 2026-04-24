<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules(): array
    {
        $enrollId = $this->route('enroll')?->id ?? $this->route('enroll');
        $courseId = $this->input('course_id');

        return [
            'course_id' => [
                'required',
                'exists:aca_courses,id',
            ],
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('aca_enrollments', 'student_id')
                    ->where(fn($query) => $query->where('course_id', $courseId))
                    ->ignore($enrollId),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'The course field is required.',
            'course_id.exists' => 'The selected course is invalid.',
            'student_id.required' => 'The student field is required.',
            'student_id.exists' => 'The selected student is invalid.',
            'student_id.unique' => 'This student is already enrolled in the selected course.',
        ];
    }
}
