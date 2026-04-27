<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class ExamRuleFormRequest extends FormRequest
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
            'type' => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'type.required'     => 'The rule type is required.',
            'type.string'       => 'The rule type must be a string.',
            'type.in'           => 'The rule type must be either "rule" or "instruction".',
            'title.required'    => 'The rule title is required.',
            'title.string'      => 'The rule title must be a string.',
            'title.max'         => 'The rule title may not be greater than 255 characters.',
            'order.integer'     => 'The order must be a valid integer.',
            'order.min'         => 'The order must be at least 0.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            // Convert empty string to null so nullable|integer passes
            'order'     => $this->input('order') !== '' ? $this->input('order') : null,
            // $this->boolean() correctly handles "0","1","true","false"
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
