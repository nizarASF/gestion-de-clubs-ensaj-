<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class MediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Le type (photo ou vidéo) est déduit du type MIME réel du fichier.
     * Une image doit être décodable : un .php renommé .jpg est donc refusé.
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:150'],
            'file' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm',
                'max:51200',
                function (string $attribute, mixed $value, callable $fail): void {
                    if (! $value instanceof UploadedFile) {
                        return;
                    }
                    $mime = $value->getMimeType();
                    if (! str_starts_with($mime, 'image/')) {
                        return;
                    }
                    if (@getimagesize($value->getRealPath()) === false) {
                        $fail('Le fichier n\'est pas une image valide.');
                    }
                    if ($value->getSize() > 5120 * 1024) {
                        $fail('Taille maximale : 5 Mo pour une image.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimetypes' => 'Formats acceptés : JPEG, PNG, WebP, MP4 ou WebM.',
            'file.max' => 'Taille maximale : 5 Mo pour une image, 50 Mo pour une vidéo.',
        ];
    }
}
