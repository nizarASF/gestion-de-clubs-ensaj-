<?php

namespace App\Http\Requests;

use App\Enums\MessageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:2000'],
            'type' => ['sometimes', 'required', Rule::enum(MessageType::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Écrivez un message.',
            'body.max' => 'Le message ne peut pas dépasser 2000 caractères.',
            'type.required' => 'Choisissez une question ou une suggestion.',
        ];
    }
}
