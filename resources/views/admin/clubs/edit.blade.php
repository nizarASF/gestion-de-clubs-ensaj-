@extends('layouts.app')
@section('title', 'Modifier ' . $club->name . ' — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Clubs', 'url' => route('admin.clubs.index')], ['label' => $club->name]]" />
    <h1>Modifier {{ $club->name }}</h1>
    <div class="card" style="max-width: 720px;">
        <form method="POST" action="{{ route('admin.clubs.update', $club) }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @method('PUT')
            @include('admin.clubs._form')
            <div class="form-actions">
                <button class="btn" type="submit">Enregistrer</button>
                <a class="btn btn--secondary" href="{{ route('admin.clubs.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
