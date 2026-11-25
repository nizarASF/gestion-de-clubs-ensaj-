@extends('layouts.app')
@section('title', 'Fiches du bureau — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Fiches du bureau']]" />
    <div class="page-head">
        <h1>Fiches du bureau</h1>
        <a class="btn" href="{{ route('manage.team.create') }}">Proposer une fiche</a>
    </div>
    @forelse($members as $member)
        <div class="card" style="margin-bottom: .75rem;">
            <div class="row spread">
                <div>
                    <strong>{{ $member->name }}</strong> — {{ $member->position }}
                    <div class="muted">{{ $member->academic_year }} · ordre {{ $member->sort_order }}</div>
                    @if($member->rejection_reason)<p class="form-error">Motif du refus : {{ $member->rejection_reason }}</p>@endif
                </div>
                <div class="row">
                    <x-status-badge :status="$member->status" />
                    <a class="btn btn--small btn--secondary" href="{{ route('manage.team.edit', $member) }}">Modifier</a>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">Aucune fiche proposée.</div>
    @endforelse
    <div class="mt-2">{{ $members->links() }}</div>
@endsection
