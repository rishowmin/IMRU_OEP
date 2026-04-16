<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseFormRequest extends FormRequest
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
    public function rules()
    {
        $courseId = $this->route('course');
        if (is_object($courseId) && property_exists($courseId, 'id')) {
            $courseId = $courseId->id;
        }

        return [
            'course_title' => 'required|string|max:255',
            'course_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('aca_courses', 'course_code')->ignore($courseId),
            ],
            'credits' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'course_title.required' => 'The course title is required.',
            'course_title.string' => 'The course title must be a string.',
            'course_title.max' => 'The course title may not be greater than 255 characters.',
            'course_code.string' => 'The course code must be a string.',
            'course_code.max' => 'The course code may not be greater than 255 characters.',
            'course_code.unique' => 'The course code has already been taken.',
            'credits.string' => 'The credits must be a string.',
            'credits.max' => 'The credits may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
        ];
    }
}
