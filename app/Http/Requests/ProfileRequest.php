<?php

namespace App\Http\Requests;

use App\Enums\FieldOfStudy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'field_of_study' => ['required', Rule::enum(FieldOfStudy::class)],
        ];
    }
}
