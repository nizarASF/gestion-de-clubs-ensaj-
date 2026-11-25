<div class="form-row">
    <label for="name">Nom du club</label>
    <input id="name" type="text" name="name" value="{{ old('name', $club->name) }}" required maxlength="100">
    @error('name')<p class="form-error">{{ $message }}</p>@enderror
</div>
@if($club->exists)
    <div class="form-row">
        <label>Slug (non modifiable)</label>
        <input type="text" value="{{ $club->slug }}" readonly>
    </div>
@endif
<div class="form-row">
    <label for="short_description">Description courte</label>
    <input id="short_description" type="text" name="short_description" value="{{ old('short_description', $club->short_description) }}" required maxlength="300">
    @error('short_description')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="description">Description complète</label>
    <textarea id="description" name="description" required>{{ old('description', $club->description) }}</textarea>
    @error('description')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="logo">Logo (JPEG, PNG ou WebP, 5 Mo max)</label>
    <input id="logo" type="file" name="logo" accept="image/jpeg,image/png,image/webp" data-preview>
    @if($club->logo_path)
        <img class="image-preview" src="{{ Storage::disk('public')->url($club->logo_path) }}" alt="Logo actuel">
    @endif
    @error('logo')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="checkbox-row">
    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $club->exists ? $club->is_active : true))>
    <label for="is_active">Club actif (visible sur le site)</label>
</div>
