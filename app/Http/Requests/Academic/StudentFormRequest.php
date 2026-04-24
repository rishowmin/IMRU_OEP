<?php

namespace App\Http\Requests\Academic;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentFormRequest extends FormRequest
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
        $studentId = $this->route('student')?->id ?? $this->route('student');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'username'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'username')->ignore($studentId),
            ],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($studentId),
            ],
            // 'password' => ['required', Password::defaults()],
            'password' => $this->isMethod('PUT')
                ? ['sometimes', 'nullable', 'string', Password::defaults()]
                : ['required', 'string', Password::defaults()],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            // 'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
