<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TeacherIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow access
    }

    public function rules(): array
    {
        return [
            'search'   => 'nullable|string|max:255',
            'gender'   => 'nullable|in:male,female',
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_by'  => 'nullable|string|in:first_name,last_name,email,date_of_birth,created_at',
            'sort_dir' => 'nullable|string|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'gender.in'    => 'gender_invalid',
            'search.string'=> 'search_string',
            'search.max'   => 'search_max',
            'page.integer' => 'page_integer',
            'page.min'     => 'page_min',
            'per_page.integer' => 'per_page_integer',
            'per_page.min'     => 'per_page_min',
            'per_page.max'     => 'per_page_max',
            'sort_by.in'       => 'sort_by_invalid',
            'sort_dir.in'      => 'sort_dir_invalid',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors'  => $errors,
            ], 422)
        );
    }
}
