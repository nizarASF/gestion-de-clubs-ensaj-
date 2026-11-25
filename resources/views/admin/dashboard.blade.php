@extends('layouts.app')
@section('title', 'Administration — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration']]" />
    <div class="page-head"><h1>Tableau de bord BDE</h1></div>
    <div class="grid grid-4">
        @foreach(['user' => 'Inscriptions', 'post' => 'Posts', 'team' => 'Fiches du bureau', 'media' => 'Médias'] as $key => $label)
            <a class="stat-card" href="{{ route('admin.moderation', ['tab' => $key]) }}">
                <div class="stat-card__number">{{ $counts[$key] }}</div>
                <div class="stat-card__label">{{ $label }} en attente</div>
            </a>
        @endforeach
    </div>
    <div class="section row">
        <a class="btn" href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <a class="btn" href="{{ route('admin.clubs.index') }}">Clubs</a>
        <a class="btn" href="{{ route('admin.posts.index') }}">Posts</a>
        <a class="btn btn--secondary" href="{{ route('admin.moderation') }}">Modération</a>
    </div>
@endsection
