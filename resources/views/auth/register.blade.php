@extends('layouts.app')
@section('title', "S'inscrire — Ensaj Clubs")
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Inscription']]" />
    <div class="card" style="max-width: 560px; margin: 0 auto;">
        <h1>S'inscrire</h1>
        <p class="muted">Votre compte sera validé par le BDE avant votre première connexion.</p>
        <form method="POST" action="{{ route('register') }}" class="form-grid" data-password-confirm>
            @csrf
            <div class="form-row">
                <label for="first_name">Prénom</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required maxlength="100">
                @error('first_name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="last_name">Nom</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required maxlength="100">
                @error('last_name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="field_of_study">Filière</label>
                <select id="field_of_study" name="field_of_study" required>
                    <option value="">— Choisissez votre filière —</option>
                    @foreach(\App\Enums\FieldOfStudy::cases() as $field)
                        <option value="{{ $field->value }}" @selected(old('field_of_study') === $field->value)>{{ $field->label() }}</option>
                    @endforeach
                </select>
                @error('field_of_study')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="password">Mot de passe (8 caractères minimum)</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
            </div>
            <div class="form-actions">
                <button class="btn" type="submit">S'inscrire</button>
                <a class="btn btn--secondary" href="{{ route('login') }}">Déjà un compte ?</a>
            </div>
        </form>
    </div>
@endsection
