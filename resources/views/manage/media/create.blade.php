@extends('layouts.app')
@section('title', 'Ajouter un média — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Médias', 'url' => route('manage.media.index')], ['label' => 'Ajouter']]" />
    <h1>Ajouter un média</h1>
    <div class="card" style="max-width: 640px;">
        <form method="POST" action="{{ route('manage.media.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            <div class="form-row">
                <label for="title">Titre (facultatif)</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" maxlength="150">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="file">Fichier (image 5 Mo max ou vidéo 50 Mo max)</label>
                <input id="file" type="file" name="file" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" required data-preview>
                @error('file')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-actions"><button class="btn" type="submit">Envoyer au BDE</button></div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
