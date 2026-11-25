<div class="form-row">
    <label for="title">Titre</label>
    <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}" required maxlength="200">
    @error('title')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div class="form-row">
    <label for="body">Texte du post</label>
    <textarea id="body" name="body" required maxlength="10000">{{ old('body', $post->body) }}</textarea>
    @error('body')<p class="form-error">{{ $message }}</p>@enderror
</div>
@if($post->exists && $post->images->isNotEmpty())
    <div class="form-row">
        <label>Images actuelles (cocher pour supprimer)</label>
        <div class="post-images">
            @foreach($post->images as $image)
                <div>
                    <img src="{{ Storage::disk('public')->url($image->thumbnail_path ?: $image->path) }}" alt="Image du post" loading="lazy">
                    <label class="checkbox-row"><input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Supprimer</label>
                </div>
            @endforeach
        </div>
    </div>
@endif
<div class="form-row">
    <label for="images">Ajouter des images (5 maximum au total, 5 Mo par image)</label>
    <input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple data-preview>
    @error('images')<p class="form-error">{{ $message }}</p>@enderror
    @error('images.*')<p class="form-error">{{ $message }}</p>@enderror
</div>
