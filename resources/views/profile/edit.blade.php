@extends('layouts.app')
@section('title', 'Mon profil — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Mon profil']]" />
    <h1>Mon profil</h1>
    <div class="grid grid-2">
        <section class="card">
            <h2>Informations personnelles</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="form-grid">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <label for="first_name">Prénom</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="last_name">Nom</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    @error('last_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="email">Adresse email (non modifiable)</label>
                    <input id="email" type="email" value="{{ $user->email }}" readonly>
                </div>
                <div class="form-row">
                    <label for="field_of_study">Filière</label>
                    <select id="field_of_study" name="field_of_study" required>
                        @foreach(\App\Enums\FieldOfStudy::cases() as $field)
                            <option value="{{ $field->value }}" @selected(old('field_of_study', $user->field_of_study->value) === $field->value)>{{ $field->label() }}</option>
                        @endforeach
                    </select>
                    @error('field_of_study')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-actions"><button class="btn" type="submit">Enregistrer</button></div>
            </form>
        </section>
        <section class="card">
            <h2>Changer le mot de passe</h2>
            <form method="POST" action="{{ route('profile.password') }}" class="form-grid" data-password-confirm>
                @csrf
                @method('PUT')
                <div class="form-row">
                    <label for="current_password">Ancien mot de passe</label>
                    <input id="current_password" type="password" name="current_password" required autocomplete="current-password">
                    @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="password">Nouveau mot de passe</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-row">
                    <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
                </div>
                <div class="form-actions"><button class="btn" type="submit">Modifier le mot de passe</button></div>
            </form>
        </section>
    </div>
@endsection
