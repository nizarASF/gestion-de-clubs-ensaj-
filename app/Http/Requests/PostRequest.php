<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Présent uniquement lorsque le BDE crée un post pour un club.
            'club_id' => ['sometimes', 'required', 'exists:clubs,id'],
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:10000'],
            'images' => ['sometimes', 'array', 'max:5'],
            'images.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'remove_images' => ['sometimes', 'array'],
            'remove_images.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.max' => 'Un post ne peut pas contenir plus de 5 images.',
            'images.*.mimetypes' => 'Seuls les formats JPEG, PNG et WebP sont acceptés.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5 Mo.',
        ];
    }
}
