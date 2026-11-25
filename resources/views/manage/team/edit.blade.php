@extends('layouts.app')
@section('title', 'Modifier la fiche — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Fiches du bureau', 'url' => route('manage.team.index')], ['label' => 'Modifier']]" />
    <h1>Modifier la fiche de {{ $member->name }}</h1>
    @if($member->rejection_reason)<div class="alert alert--danger">Motif du refus : {{ $member->rejection_reason }}</div>@endif
    <div class="card" style="max-width: 720px;">
        <form method="POST" action="{{ route('manage.team.update', $member) }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            @method('PUT')
            @include('manage.team._form', ['member' => $member])
            <div class="form-actions"><button class="btn" type="submit">Enregistrer</button></div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/upload-preview.js') }}" defer></script>@endpush
