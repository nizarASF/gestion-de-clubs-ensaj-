@extends('layouts.app')
@section('title', 'Notifications — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Notifications']]" />
    <div class="page-head"><h1>Notifications</h1></div>
    <div class="stack">
        @forelse($notifications as $notification)
            <a class="card @if($notification->read_at) @else alert--success @endif" href="{{ route('notifications.read', $notification->id) }}">
                <strong>{{ $notification->data['message'] ?? 'Notification' }}</strong>
                <div class="muted">{{ $notification->created_at?->format('d/m/Y H:i') }} @if($notification->read_at) · Lue @endif</div>
            </a>
        @empty
            <div class="empty-state">Aucune notification pour le moment.</div>
        @endforelse
    </div>
    <div class="mt-2">{{ $notifications->links() }}</div>
@endsection
