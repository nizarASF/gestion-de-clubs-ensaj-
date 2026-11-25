<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Changement de rôle par le BDE (le club est obligatoire pour un membre). */
class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBde() === true;
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'in:student,club_member,bde'],
            'club_id' => ['required_if:role,club_member', 'nullable', 'exists:clubs,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Choisissez un rôle.',
            'role.in' => 'Rôle invalide.',
            'club_id.required_if' => 'Choisissez un club pour un membre.',
            'club_id.exists' => 'Ce club n\'existe pas.',
        ];
    }
}
