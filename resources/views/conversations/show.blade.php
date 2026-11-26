@extends('layouts.app')
@section('title', 'Conversation — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Mes conversations', 'url' => route('conversations.index')], ['label' => 'Conversation']]" />
    <div class="page-head">
        <h1>@if($isInitiator) {{ $conversation->club->name }} @else {{ $conversation->user->full_name }} @endif</h1>
        <a class="btn btn--small btn--secondary" href="{{ route('conversations.index') }}">Retour aux conversations</a>
    </div>

    <div class="chat" id="chat"
         data-store-url="{{ route('conversations.messages.store', $conversation) }}"
         data-fetch-url="{{ route('conversations.messages.index', $conversation) }}">
        <div class="chat__messages" id="chat-messages" data-last-id="{{ $conversation->messages->last()?->id ?? 0 }}">
            @forelse($conversation->messages as $message)
                @php
                    $mine = $message->author_id === auth()->id();
                    $unread = ! $mine && $message->read_at === null && $message->from_club === $isInitiator;
                @endphp
                <article class="bubble @if($mine) bubble--mine @endif @if($unread) bubble--unread @endif">
                    <div class="bubble__head">
                        <strong>{{ $message->author->full_name }}</strong>
                        <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                        @if($message->type)<span class="badge badge--info">{{ $message->type->label() }}</span>@endif
                        @if($unread)<span class="badge badge--pending">Non lu</span>@endif
                    </div>
                    <div class="bubble__body">{{ $message->body }}</div>
                </article>
            @empty
                <p class="chat__empty">Aucun message. Écrivez le premier !</p>
            @endforelse
        </div>

        <form id="chat-form" class="card form-grid">
            @csrf
            @if($isInitiator)
                <div class="form-row">
                    <label for="chat-type">Type de message</label>
                    <select id="chat-type" name="type">
                        @foreach(\App\Enums\MessageType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            @endif
            <div class="form-row">
                <label for="chat-body">Votre message</label>
                <textarea id="chat-body" name="body" required maxlength="2000" rows="3"></textarea>
                @error('body')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-actions"><button class="btn" type="submit">Envoyer</button></div>
        </form>
    </div>
@endsection
@push('scripts')<script src="{{ asset('js/chat.js') }}" defer></script>@endpush
