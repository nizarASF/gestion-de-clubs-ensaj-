@extends('layouts.app')
@section('title', 'Contacter ' . $club->name . ' — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name, 'url' => route('clubs.show', $club)], ['label' => 'Contacter le club']]" />
    <h1>Contacter {{ $club->name }}</h1>
    <div class="card" style="max-width: 720px;">
        <form method="POST" action="{{ route('conversations.store', $club) }}" class="form-grid">
            @csrf
            <div class="form-row">
                <label for="type">Votre message est</label>
                <select id="type" name="type" required>
                    <option value="">— Choisir —</option>
                    @foreach(\App\Enums\MessageType::cases() as $type)
                        <option value="{{ $type->value }}" @selected(old('type') === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="body">Message (1 à 2000 caractères)</label>
                <textarea id="body" name="body" required maxlength="2000">{{ old('body') }}</textarea>
                @error('body')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-actions"><button class="btn" type="submit">Envoyer</button></div>
        </form>
    </div>
@endsection
