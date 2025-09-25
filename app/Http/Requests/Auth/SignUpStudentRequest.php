<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SignUpStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // User fields
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|max:255|confirmed',
            'gender'        => 'required|string|max:20',


            // Student fields
            'phone_number'  => 'required|string|max:255',
            'second_number' => 'nullable|string|max:255',
            'parent_number' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address'       => 'nullable|string|max:255',
            'year_of_study' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            // User
            'first_name.required' => 'first_name_required',
            'first_name.string'   => 'first_name_string',
            'first_name.max'      => 'first_name_max',

            'last_name.required'  => 'last_name_required',
            'last_name.string'    => 'last_name_string',
            'last_name.max'       => 'last_name_max',

            'email.required'      => 'email_required',
            'email.email'         => 'email_email',
            'email.unique'        => 'email_unique',

            'password.required'   => 'password_required',
            'password.string'     => 'password_string',
            'password.min'        => 'password_min',
            'password.max'        => 'password_max',
            'password.confirmed'  => 'password_confirmed',

            'gender.required'        => 'gender_required',
            'gender.string'          => 'gender_string',
            'gender.max'             => 'gender_max',

            // Student
            'phone_number.required'  => 'phone_number_required',
            'phone_number.string'    => 'phone_number_string',
            'phone_number.max'       => 'phone_number_max',

            'second_number.string'   => 'second_number_string',
            'second_number.max'      => 'second_number_max',

            'parent_number.required' => 'parent_number_required',
            'parent_number.string'   => 'parent_number_string',
            'parent_number.max'      => 'parent_number_max',

            'date_of_birth.required' => 'date_of_birth_required',
            'date_of_birth.date'     => 'date_of_birth_date',

            'address.string'         => 'address_string',
            'address.max'            => 'address_max',

            'year_of_study.required' => 'year_of_study_required',
            'year_of_study.string'   => 'year_of_study_string',
            'year_of_study.max'      => 'year_of_study_max',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors'  => $errors
            ], 422)
        );
    }
}
