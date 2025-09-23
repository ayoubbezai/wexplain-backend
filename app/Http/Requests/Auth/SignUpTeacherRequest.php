<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SignUpTeacherRequest extends FormRequest
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

            // Teacher required fields
            'gender'        => 'required|string|max:20',
            'phone_number'  => 'required|string|max:30',
            'nationality'   => 'required|string|max:100',
            'date_of_birth' => 'required|date',

            // Optional fields
            'second_phone_number' => 'nullable|string|max:30',
            'address'             => 'nullable|string|max:1000',

            // CCP (all required if one exists)
            'ccp_number'       => 'nullable|string|max:50|required_with:ccp_key,ccp_account_name',
            'ccp_key'          => 'nullable|string|max:50|required_with:ccp_number,ccp_account_name',
            'ccp_account_name' => 'nullable|string|max:100|required_with:ccp_number,ccp_key',

            // Card (all required if one exists)
            'card_number'      => 'nullable|string|max:30|required_with:card_expiry,card_cvv,card_holder_name',
            'card_expiry'      => 'nullable|date|required_with:card_number,card_cvv,card_holder_name',
            'card_cvv'         => 'nullable|string|max:10|required_with:card_number,card_expiry,card_holder_name',
            'card_holder_name' => 'nullable|string|max:100|required_with:card_number,card_expiry,card_cvv',

            // File uploads
            'teacher_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'id_card_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'cv_pdf'        => 'required|file|mimes:pdf|max:5120',

            // Teaching info
            'primary_subject'     => 'required|string|max:100',
            'other_subjects'      => 'nullable|string',
            'teaching_level'      => 'required|string|max:100',
            'years_of_experience' => 'required|integer|min:0',
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

            // Teacher required
            'gender.required'        => 'gender_required',
            'gender.string'          => 'gender_string',
            'gender.max'             => 'gender_max',

            'phone_number.required'  => 'phone_number_required',
            'phone_number.string'    => 'phone_number_string',
            'phone_number.max'       => 'phone_number_max',

            'nationality.required'   => 'nationality_required',
            'nationality.string'     => 'nationality_string',
            'nationality.max'        => 'nationality_max',

            'date_of_birth.required' => 'date_of_birth_required',
            'date_of_birth.date'     => 'date_of_birth_date',

            'second_phone_number.string' => 'second_phone_number_string',
            'second_phone_number.max'    => 'second_phone_number_max',

            'address.string' => 'address_string',
            'address.max'    => 'address_max',

            // CCP group
            'ccp_number.required_with'       => 'ccp_number_required_with',
            'ccp_key.required_with'          => 'ccp_key_required_with',
            'ccp_account_name.required_with' => 'ccp_account_name_required_with',

            // Card group
            'card_number.required_with'      => 'card_number_required_with',
            'card_expiry.required_with'      => 'card_expiry_required_with',
            'card_cvv.required_with'         => 'card_cvv_required_with',
            'card_holder_name.required_with' => 'card_holder_name_required_with',

            // Files
            'teacher_image.required' => 'teacher_image_required',
            'teacher_image.image'    => 'teacher_image_must_be_image',
            'teacher_image.mimes'    => 'teacher_image_invalid_type',
            'teacher_image.max'      => 'teacher_image_too_large',

            'id_card_image.required' => 'id_card_image_required',
            'id_card_image.image'    => 'id_card_image_must_be_image',
            'id_card_image.mimes'    => 'id_card_image_invalid_type',
            'id_card_image.max'      => 'id_card_image_too_large',

            'cv_pdf.required' => 'cv_pdf_required',
            'cv_pdf.file'     => 'cv_pdf_file',
            'cv_pdf.mimes'    => 'cv_pdf_must_be_pdf',
            'cv_pdf.max'      => 'cv_pdf_too_large',

            // Teaching info
            'primary_subject.required' => 'primary_subject_required',
            'primary_subject.string'   => 'primary_subject_string',
            'primary_subject.max'      => 'primary_subject_max',

            'other_subjects.string'    => 'other_subjects_string',

            'teaching_level.required'  => 'teaching_level_required',
            'teaching_level.string'    => 'teaching_level_string',
            'teaching_level.max'       => 'teaching_level_max',

            'years_of_experience.required' => 'years_of_experience_required',
            'years_of_experience.integer'  => 'years_of_experience_integer',
            'years_of_experience.min'      => 'years_of_experience_min',
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
