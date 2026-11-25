@extends('layouts.app')
@section('title', 'Modifier le post — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Posts', 'url' => route('manage.posts.index')], ['label' => 'Modifier']]" />
    <h1>Modifier le post</h1>
    <div class="row"><x-status-badge :status="$post->status" /></div>
    @if($post->rejection_reason)<div class="alert alert--danger mt-2">Motif du refus : {{ $post->rejection_reason }}</div>@endif
    <div class="card mt-2" style="max-width: 780px;">
        <form method="POST" action="{{ route('manage.posts.update', $post) }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @method('PUT')
            @include('manage.posts._form', ['post' => $post])
            <div class="form-actions">
                <button class="btn" type="submit">Enregistrer</button>
                <a class="btn btn--secondary" href="{{ route('manage.posts.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
