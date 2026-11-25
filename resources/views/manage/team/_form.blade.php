<div class="form-row">
    <label for="name">Nom complet</label>
    <input id="name" type="text" name="name" value="{{ old('name', $member->name) }}" required maxlength="150">
    @error('name')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="position">Poste</label>
    <input id="position" type="text" name="position" value="{{ old('position', $member->position) }}" required maxlength="100">
    @error('position')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="academic_year">Année académique (ex. 2025-2026)</label>
    <input id="academic_year" type="text" name="academic_year" value="{{ old('academic_year', $member->academic_year ?: '2025-2026') }}" required maxlength="9" placeholder="2025-2026">
    @error('academic_year')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="sort_order">Ordre d'affichage</label>
    <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $member->sort_order ?? 0) }}">
    @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="photo">Photo (JPEG, PNG ou WebP, 5 Mo max)</label>
    <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-preview>
    @if($member->photo_path)
        <img class="image-preview" src="{{ Storage::disk('public')->url($member->photo_path) }}" alt="Photo actuelle">
    @endif
    @error('photo')<p class="form-error">{{ $message }}</p>@enderror
</div>
