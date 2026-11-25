@extends('layouts.app')
@section('title', 'Médias du club — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Médias']]" />
    <div class="page-head">
        <h1>Médias de mon club</h1>
        <a class="btn" href="{{ route('manage.media.create') }}">Ajouter un média</a>
    </div>
    <div class="thumb-grid">
        @forelse($media as $item)
            <div class="thumb">
                <div class="thumb__badges"><x-status-badge :status="$item->status" /></div>
                @if($item->type === \App\Enums\MediaType::Photo && $item->thumbnail_path)
                    <img src="{{ Storage::disk('public')->url($item->thumbnail_path) }}" alt="{{ $item->title ?: 'Photo' }}" loading="lazy">
                @elseif($item->type === \App\Enums\MediaType::Video)
                    <video controls preload="metadata" src="{{ Storage::disk('public')->url($item->path) }}"></video>
                @else
                    <img src="{{ Storage::disk('public')->url($item->path) }}" alt="{{ $item->title ?: 'Photo' }}" loading="lazy">
                @endif
                <div class="thumb__label">
                    {{ $item->title ?: $item->type->label() }}
                    @if($item->rejection_reason)<br><span class="form-error">Motif : {{ $item->rejection_reason }}</span>@endif
                </div>
            </div>
        @empty
            <div class="empty-state">Aucun média envoyé.</div>
        @endforelse
    </div>
    <div class="mt-2">{{ $media->links() }}</div>
@endsection
