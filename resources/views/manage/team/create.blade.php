@extends('layouts.app')
@section('title', 'Proposer une fiche — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Fiches du bureau', 'url' => route('manage.team.index')], ['label' => 'Nouvelle fiche']]" />
    <h1>Proposer une fiche</h1>
    <div class="card" style="max-width: 720px;">
        <form method="POST" action="{{ route('manage.team.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @include('manage.team._form', ['member' => new \App\Models\TeamMember()])
            <div class="form-actions"><button class="btn" type="submit">Envoyer au BDE</button></div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
