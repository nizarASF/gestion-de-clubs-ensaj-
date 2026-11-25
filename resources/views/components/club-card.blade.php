@props(['club'])
<a class="club-card" href="{{ route('clubs.show', $club) }}">
    @if($club->logo_path)
        <img class="club-card__logo" src="{{ Storage::disk('public')->url($club->logo_path) }}" alt="Logo de {{ $club->name }}">
    @else
        <img class="club-card__logo" src="{{ asset('images/club-default.svg') }}" alt="Logo par défaut">
    @endif
    <h3 class="club-card__name">{{ $club->name }}</h3>
    <p class="club-card__desc">{{ $club->short_description }}</p>
    <span class="muted">Découvrir le club →</span>
</a>
