<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:100'],
            'academic_year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'photo' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
        ];
    }

    /** La seconde année doit être exactement la première + 1. */
    public function after(): array
    {
        return [function (Validator $validator) {
            $year = (string) $this->input('academic_year');
            if (preg_match('/^(\d{4})-(\d{4})$/', $year, $m) && (int) $m[2] !== (int) $m[1] + 1) {
                $validator->errors()->add('academic_year', "L'année doit être consécutive (ex. 2025-2026).");
            }
        }];
    }
}
