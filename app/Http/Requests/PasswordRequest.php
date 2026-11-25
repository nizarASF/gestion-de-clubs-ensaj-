<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

/** Validation du changement de mot de passe (ancien mot de passe obligatoire). */
class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            $current = $this->input('current_password');
            if ($current && ! Hash::check($current, $this->user()->password)) {
                $validator->errors()->add('current_password', "L'ancien mot de passe est incorrect.");
            }
        }];
    }

    public function messages(): array
    {
        return ['password.confirmed' => 'Les deux mots de passe ne correspondent pas.'];
    }
}
