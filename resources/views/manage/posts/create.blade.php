@extends('layouts.app')
@section('title', 'Nouveau post — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Posts', 'url' => route('manage.posts.index')], ['label' => 'Nouveau post']]" />
    <h1>Nouveau post</h1>
    <div class="card" style="max-width: 780px;">
        <form method="POST" action="{{ route('manage.posts.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @include('manage.posts._form', ['post' => new \App\Models\Post()])
            <div class="form-actions">
                <button class="btn" type="submit">Enregistrer le brouillon</button>
                <a class="btn btn--secondary" href="{{ route('manage.posts.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
