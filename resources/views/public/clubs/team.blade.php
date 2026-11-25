@extends('layouts.app')
@section('title', 'Meet the Team — ' . $club->name)
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name, 'url' => route('clubs.show', $club)], ['label' => 'Meet the Team']]" />
    <div class="page-head">
        <h1>Meet the Team</h1>
        <form method="GET" action="{{ route('clubs.team', $club) }}" class="row" id="team-year-form">
            <div class="form-row">
                <label for="year">Année académique</label>
                <select id="year" name="year">
                    @forelse($years as $option)
                        <option value="{{ $option }}" @selected($option === $year)>{{ $option }}</option>
                    @empty
                        <option value="">Aucune année disponible</option>
                    @endforelse
                </select>
            </div>
            <noscript><button class="btn" type="submit">Afficher</button></noscript>
        </form>
    </div>

    <div class="grid grid-cards">
        @forelse($members as $member)
            <article class="team-card">
                <x-avatar-initials :name="$member->name" :photo="$member->photo_path" />
                <h3>{{ $member->name }}</h3>
                <p class="muted">{{ $member->position }}</p>
                <small>{{ $member->academic_year }}</small>
            </article>
        @empty
            <div class="empty-state">Aucune fiche validée pour cette année.</div>
        @endforelse
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/team-year.js') }}" defer></script>@endpush
