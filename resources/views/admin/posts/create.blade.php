@extends('layouts.app')
@section('title', 'Publier un post — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Posts', 'url' => route('admin.posts.index')], ['label' => 'Publier']]" />
    <h1>Publier un post</h1>
    <div class="card" style="max-width: 780px;">
        <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            <div class="form-row">
                <label for="club_id">Club</label>
                <select id="club_id" name="club_id" required>
                    <option value="">— Choisir un club —</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}" @selected(old('club_id') == $club->id)>{{ $club->name }}</option>
                    @endforeach
                </select>
                @error('club_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="title">Titre</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="200">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="body">Texte</label>
                <textarea id="body" name="body" required maxlength="10000">{{ old('body') }}</textarea>
                @error('body')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="images">Images (5 max, 5 Mo par image)</label>
                <input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple data-preview>
                @error('images')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-actions"><button class="btn" type="submit">Publier</button></div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
