@extends('layouts.app')
@section('title', 'Accès refusé — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Accès refusé']]" />
    <div class="card center" style="padding: 3rem 1rem;">
        <h1>403 — Accès refusé</h1>
        <p class="muted">{{ $exception->getMessage() ?: "Vous n'avez pas le droit d'accéder à cette page." }}</p>
        <a class="btn" href="{{ route('home') }}">Retour à l'accueil</a>
    </div>
@endsection
