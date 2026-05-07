<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class QuestionLibraryFormRequest extends FormRequest
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
            'topic' => 'required|string',
            'question_type' => 'required|string',
            'difficulty_level' => 'required|string',
            'marks' => 'required|numeric|min:1|max:100',
            'question_text' => 'required|string',
            'option_a' => 'nullable|string',
            'option_b' => 'nullable|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'question_figure' => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'topic.required' => 'The topic is required.',
            'topic.string' => 'The topic must be a string.',
            'question_type.required' => 'The question type is required.',
            'question_type.string' => 'The question type must be a string.',
            'question_text.required' => 'The question text is required.',
            'question_text.string' => 'The question text must be a string.',
            'difficulty_level.required' => 'The difficulty level is required.',
            'difficulty_level.string' => 'The difficulty level must be a string.',
            'marks.required' => 'The marks are required.',
            'marks.numeric' => 'The marks must be a number.',
            'marks.min' => 'The marks must be at least 1.',
            'marks.max' => 'The marks may not be greater than 100.',
            'option_a.string' => 'Option A must be a string.',
            'option_b.string' => 'Option B must be a string.',
            'option_c.string' => 'Option C must be a string.',
            'option_d.string' => 'Option D must be a string.',
            'correct_answer.string' => 'Correct answer must be a string.',
            'question_figure.image' => 'Question figure must be an image file.',
            'question_figure.max' => 'Question figure may not be greater than 2MB.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
