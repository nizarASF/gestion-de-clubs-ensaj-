@extends('layouts.app')
@section('title', 'Se connecter — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Se connecter']]" />
    <div class="card" style="max-width: 480px; margin: 0 auto;">
        <h1>Se connecter</h1>
        <form method="POST" action="{{ route('login') }}" class="form-grid">
            @csrf
            <div class="form-row">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="checkbox-row">
                <input id="remember" type="checkbox" name="remember" value="1">
                <label for="remember">Rester connecté</label>
            </div>
            <div class="form-actions">
                <button class="btn" type="submit">Se connecter</button>
                <a class="btn btn--secondary" href="{{ route('register') }}">Créer un compte</a>
            </div>
        </form>
    </div>
@endsection
