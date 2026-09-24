<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Authorization is enforced by the DepartmentPolicy in the controller action.
 */
class StoreDepartmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'faculty_id' => ['required', 'integer', 'exists:faculties,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->where(
                    fn (Builder $query) => $query->where('faculty_id', $this->integer('faculty_id')),
                ),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('This faculty already has a department with that name.'),
        ];
    }
}
