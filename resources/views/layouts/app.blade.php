<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ensaj Clubs')</title>
    <link rel="icon" href="{{ asset('images/club-default.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="{{ route('home') }}">Ensaj<span class="brand-dot">Clubs</span></a>
        <button class="nav-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
        <nav class="site-nav" aria-label="Navigation principale">
            <ul>
                <li><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">Se connecter</a></li>
                    <li><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
                @endguest
                @auth
                    <li><a class="nav-link" href="{{ route('profile.edit') }}"><span class="user-chip">{{ Auth::user()->full_name }}</span></a></li>
                    <li>
                        <a class="nav-link" href="{{ route('notifications.index') }}" aria-label="Notifications">
                            <span class="nav-icon">🔔</span><span>Notifications</span>
                            @if(($unreadNotificationsCount ?? 0) > 0)<span class="nav-count">{{ $unreadNotificationsCount }}</span>@endif
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('conversations.index') }}" aria-label="Messages">
                            <span class="nav-icon">✉️</span><span>Messages</span>
                            @if(($unreadMessagesCount ?? 0) > 0)<span class="nav-count">{{ $unreadMessagesCount }}</span>@endif
                        </a>
                    </li>
                    @if(Auth::user()->isClubMember())
                        <li><a class="nav-link" href="{{ route('manage.dashboard') }}">Espace membre</a></li>
                    @endif
                    @if(Auth::user()->isBde())
                        <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Administration</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="nav-link btn--link" type="submit">Se déconnecter</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    @include('components.flash')
    @yield('content')
</main>

<footer class="site-footer">
    <div>Ensaj Clubs — Projet universitaire de développement web · <a href="{{ route('home') }}">Accueil</a></div>
</footer>

<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
