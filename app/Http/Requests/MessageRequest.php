<?php

namespace App\Http\Requests;

use App\Enums\MessageType;
use App\Models\Conversation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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

    /**
     * Le type est obligatoire pour un initiateur ; un membre du club qui répond
     * n'a pas à choisir question/suggestion.
     */
    public function after(): array
    {
        return [function (Validator $validator): void {
            $conversation = $this->route('conversation');
            $isClubReply = $conversation instanceof Conversation
                && $conversation->user_id !== $this->user()?->id;

            if (! $isClubReply && ! $this->filled('type')) {
                $validator->errors()->add('type', 'Choisissez une question ou une suggestion.');
            }
        }];
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
