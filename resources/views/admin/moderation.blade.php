@extends('layouts.app')
@section('title', 'Modération — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Modération']]" />
    <div class="page-head"><h1>Modération</h1></div>

    <nav class="tabs" aria-label="Onglets de modération">
        @foreach($tabs as $key => $label)
            <a class="@if($tab === $key) is-active @endif" href="{{ route('admin.moderation', ['tab' => $key]) }}">
                {{ ucfirst($label) }} <span class="badge badge--pending">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    @forelse($items as $item)
        @php
            $name = match($tab) {
                'user' => $item->full_name . ' — ' . $item->email,
                'post' => $item->title . ' (' . $item->club->name . ')',
                'team' => $item->name . ' — ' . $item->position . ' (' . $item->club->name . ')',
                'media' => ($item->title ?: $item->type->label()) . ' (' . $item->club->name . ')',
            };
        @endphp
        <div class="card" style="margin-bottom: .75rem;">
            <div class="row spread">
                <div>
                    <strong>{{ $name }}</strong>
                    <div class="muted">Proposé le {{ $item->created_at?->format('d/m/Y H:i') }}</div>
                </div>
                <x-status-badge :status="$item->status" />
            </div>
            <div class="row mt-2">
                <form method="POST" action="{{ route('admin.moderation.approve', [$tab, $item->id]) }}">
                    @csrf
                    <button class="btn btn--small" type="submit">Valider</button>
                </form>
                <form method="POST" action="{{ route('admin.moderation.reject', [$tab, $item->id]) }}" class="row" style="flex: 1;">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Motif du refus (obligatoire)" value="{{ old('rejection_reason') }}" required maxlength="1000" style="flex: 1; min-width: 200px; padding: .45rem .6rem; border: 1px solid var(--color-border); border-radius: var(--radius);">
                    <button class="btn btn--small btn--danger" type="submit">Refuser</button>
                </form>
            </div>
            @error('rejection_reason')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    @empty
        <div class="empty-state">Rien en attente dans cet onglet.</div>
    @endforelse
    <div class="mt-2">{{ $items->links() }}</div>
@endsection
