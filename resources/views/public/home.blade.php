@extends('layouts.app')
@section('title', 'Accueil — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil']]" />
    <section class="hero">
        <h1>Welcome to Ensaj Clubs</h1>
        <p>Découvrez les 11 clubs de l'ENSAJ, leurs actualités, leurs équipes et leur galerie.</p>
    </section>
    <div class="section-head"><h2>Nos clubs</h2><span class="muted">{{ $clubs->count() }} clubs actifs</span></div>
    <div class="grid grid-cards">
        @forelse($clubs as $club)
            <x-club-card :club="$club" />
        @empty
            <div class="empty-state">Aucun club actif pour le moment.</div>
        @endforelse
    </div>
@endsection
