<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ExamRuleFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Get the rule ID for update (ignore self on unique check)
        $ruleId = $this->route('examRule') ? $this->route('examRule')->id : null;

        return [
            'type'        => ['required', 'string', 'in:rule,instruction'],
            'key'         => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-z0-9_]+$/', // only lowercase letters, numbers, underscores
                Rule::unique('aca_exam_rules', 'key')->ignore($ruleId)->whereNull('deleted_at'),
            ],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order'       => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'type.required'     => 'The rule type is required.',
            'type.string'       => 'The rule type must be a string.',
            'type.in'           => 'The rule type must be either "rule" or "instruction".',
            'key.unique'        => 'This key already exists. Please choose a different key.',
            'key.regex'         => 'The key may only contain lowercase letters, numbers, and underscores (e.g. browser_minimized).',
            'key.max'           => 'The key may not be greater than 100 characters.',
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
            'order'     => $this->input('order') !== '' ? $this->input('order') : null,
            'is_active' => $this->boolean('is_active'),
            'key'       => $this->input('key') ?: Str::slug($this->input('title'), '_'),
        ]);
    }
}
