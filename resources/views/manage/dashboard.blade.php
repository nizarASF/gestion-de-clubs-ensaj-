@extends('layouts.app')
@section('title', 'Espace membre — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre']]" />
    <div class="page-head"><h1>Espace membre — {{ $club->name }}</h1></div>

    <div class="grid grid-4">
        <div class="stat-card"><div class="stat-card__number">{{ $posts['draft'] }}</div><div class="stat-card__label">Brouillons</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $posts['pending'] }}</div><div class="stat-card__label">Posts en attente</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $posts['approved'] }}</div><div class="stat-card__label">Posts publiés</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $posts['rejected'] }}</div><div class="stat-card__label">Posts refusés</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $mediaPending }}</div><div class="stat-card__label">Médias en attente</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $mediaRejected }}</div><div class="stat-card__label">Médias refusés</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $teamPending }}</div><div class="stat-card__label">Fiches en attente</div></div>
        <div class="stat-card"><div class="stat-card__number">{{ $unread }}</div><div class="stat-card__label">Messages non lus</div></div>
    </div>
    <div class="section row">
        <a class="btn" href="{{ route('manage.posts.create') }}">Nouveau post</a>
        <a class="btn" href="{{ route('manage.team.create') }}">Proposer une fiche</a>
        <a class="btn" href="{{ route('manage.media.create') }}">Ajouter un média</a>
        <a class="btn btn--secondary" href="{{ route('conversations.index') }}">Boîte du club</a>
    </div>
@endsection
