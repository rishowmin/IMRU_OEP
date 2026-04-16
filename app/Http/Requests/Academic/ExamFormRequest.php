<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class ExamFormRequest extends FormRequest
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
        return [
            'course_id' => 'required|exists:aca_courses,id',
            'exam_title' => 'nullable|string|max:255',
            'exam_code' => 'nullable|string|max:255|unique:aca_exams,exam_code,' . ($this->exam->id ?? 'NULL') . ',id',
            'exam_type' => 'nullable|string|max:255',
            'exam_date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'exam_duration_min' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|numeric|min:0',
            'passing_marks' => 'nullable|numeric|min:0|max:' . ($this->total_marks ?? 99999999),
            'total_questions' => 'nullable|integer|min:0',
            'instructions' => 'nullable|string',
            'basic_rules' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'The course is required.',
            'course_id.exists' => 'The selected course is invalid.',
            'exam_title.string' => 'The exam title must be a string.',
            'exam_title.max' => 'The exam title may not be greater than 255 characters.',
            'exam_code.string' => 'The exam code must be a string.',
            'exam_code.max' => 'The exam code may not be greater than 255 characters.',
            'exam_code.unique' => 'The exam code has already been taken.',
            'exam_type.string' => 'The exam type must be a string.',
            'exam_type.max' => 'The exam type may not be greater than 255 characters.',
            'exam_date.date' => 'The exam date is not a valid date.',
            'start_time.date_format' => 'The start time does not match the format H:i.',
            'end_time.date_format' => 'The end time does not match the format H:i.',
            'end_time.after' => 'The end time must be after the start time.',
            'exam_duration_min.integer' => 'The exam duration must be an integer.',
            'exam_duration_min.min' => 'The exam duration must be at least 1 minute.',
            'total_marks.numeric' => 'The total marks must be a number.',
            'total_marks.min' => 'The total marks must be at least 0.',
            'passing_marks.numeric' => 'The passing marks must be a number.',
            'passing_marks.min' => 'The passing marks must be at least 0.',
            'passing_marks.max' => 'The passing marks may not be greater than total marks.',
            'total_questions.integer' => 'The total questions must be an integer.',
            'total_questions.min' => 'The total questions must be at least 0.',
            'instructions.string' => 'The instructions must be a string.',
            'basic_rules.string' => 'The basic rules must be a string.',
        ];
    }
}
