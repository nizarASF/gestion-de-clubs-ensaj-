<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isBde() === true;
    }

    /** Le slug est toujours calculé depuis le nom, et n'est jamais éditable. */
    protected function prepareForValidation(): void
    {
        $club = $this->route('club');
        $this->merge([
            'slug' => $club ? $club->slug : Str::slug((string) $this->input('name')),
        ]);
    }

    public function rules(): array
    {
        $club = $this->route('club');
        $slugRule = Rule::unique('clubs', 'slug');
        if ($club) {
            $slugRule->ignore($club->id);
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/u', $slugRule],
            'short_description' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string'],
            'logo' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
