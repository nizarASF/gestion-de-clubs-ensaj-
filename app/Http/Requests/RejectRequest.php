<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBde() === true;
    }

    public function rules(): array
    {
        return ['rejection_reason' => ['required', 'string', 'max:1000']];
    }

    public function messages(): array
    {
        return ['rejection_reason.required' => 'Le motif du refus est obligatoire.'];
    }
}
