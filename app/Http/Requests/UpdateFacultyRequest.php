<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Authorization is enforced by the FacultyPolicy in the controller action.
 */
class UpdateFacultyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faculties', 'name')->ignore($this->route('faculty')),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
