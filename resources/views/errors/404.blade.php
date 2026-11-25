@extends('layouts.app')
@section('title', 'Page introuvable — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Page introuvable']]" />
    <div class="card center" style="padding: 3rem 1rem;">
        <h1>404 — Page introuvable</h1>
        <p class="muted">Cette page n'existe pas ou le club demandé est indisponible.</p>
        <a class="btn" href="{{ route('home') }}">Retour à l'accueil</a>
    </div>
@endsection
