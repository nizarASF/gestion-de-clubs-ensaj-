@extends('layouts.app')
@section('title', 'Mes conversations — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Mes conversations']]" />
    <div class="page-head"><h1>Mes conversations</h1></div>

    <section class="section">
        <h2>Fils que j'ai ouverts</h2>
        <div class="stack">
            @forelse($sent as $conversation)
                <a class="card row spread" href="{{ route('conversations.show', $conversation) }}">
                    <span><strong>{{ $conversation->club->name }}</strong><br><small class="muted">{{ $conversation->last_message_at?->format('d/m/Y H:i') }}</small></span>
                    @if($conversation->unread_count > 0)<span class="nav-count">{{ $conversation->unread_count }}</span>@endif
                </a>
            @empty
                <div class="empty-state">Vous n'avez pas encore contacté de club.</div>
            @endforelse
        </div>
    </section>

    @if(auth()->user()->isClubMember())
        <section class="section">
            <h2>Messages reçus par mon club</h2>
            <div class="stack">
                @forelse($received as $conversation)
                    <a class="card row spread" href="{{ route('conversations.show', $conversation) }}">
                        <span><strong>{{ $conversation->user->full_name }}</strong><br><small class="muted">{{ $conversation->last_message_at?->format('d/m/Y H:i') }}</small></span>
                        @if($conversation->unread_count > 0)<span class="nav-count">{{ $conversation->unread_count }}</span>@endif
                    </a>
                @empty
                    <div class="empty-state">Aucun message reçu par votre club.</div>
                @endforelse
            </div>
        </section>
    @endif
@endsection
