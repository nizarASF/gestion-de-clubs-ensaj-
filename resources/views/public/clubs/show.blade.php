@extends('layouts.app')
@section('title', $club->name . ' — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name]]" />

    <section class="card">
        <div class="row">
            @if($club->logo_path)
                <img src="{{ Storage::disk('public')->url($club->logo_path) }}" alt="Logo de {{ $club->name }}" style="width: 90px; height: 90px; object-fit: contain;">
            @else
                <img src="{{ asset('images/club-default.svg') }}" alt="Logo par défaut" style="width: 90px; height: 90px;">
            @endif
            <div>
                <h1>Introduction de {{ $club->name }}</h1>
                <p class="muted">{{ $club->short_description }}</p>
            </div>
        </div>
        <div class="mt-2">{!! nl2br(e($club->description)) !!}</div>
    </section>

    @if($media->isNotEmpty())
        <section class="section" id="galerie">
            <div class="section-head"><h2>Réels / vidéos</h2><span class="muted">{{ $media->count() }} média(s)</span></div>
            <div class="thumb-grid" id="gallery-grid">
                @foreach($media as $item)
                    <div class="thumb">
                        @if($item->type === \App\Enums\MediaType::Photo && $item->thumbnail_path)
                            <button class="thumb-button" type="button" data-lightbox-src="{{ Storage::disk('public')->url($item->path) }}" aria-label="Agrandir {{ $item->title ?: 'la photo' }}">
                                <img src="{{ Storage::disk('public')->url($item->thumbnail_path) }}" alt="{{ $item->title ?: 'Photo du club' }}" loading="lazy">
                            </button>
                        @elseif($item->type === \App\Enums\MediaType::Video)
                            <video controls preload="metadata" src="{{ Storage::disk('public')->url($item->path) }}" aria-label="{{ $item->title ?: 'Vidéo du club' }}"></video>
                        @else
                            <img src="{{ Storage::disk('public')->url($item->path) }}" alt="{{ $item->title ?: 'Photo du club' }}" loading="lazy">
                        @endif
                        @if($item->title)<div class="thumb__label">{{ $item->title }}</div>@endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section" id="posts">
        <div class="section-head"><h2>Actualités</h2></div>
        @forelse($posts as $post)
            <article class="post-card">
                <h3>{{ $post->title }}</h3>
                <div class="post-card__meta">Par {{ $post->author->full_name }} · {{ $post->published_at?->format('d/m/Y H:i') }}</div>
                <div>{!! nl2br(e($post->body)) !!}</div>
                @if($post->images->isNotEmpty())
                    <div class="post-images">
                        @foreach($post->images as $image)
                            <img src="{{ Storage::disk('public')->url($image->thumbnail_path ?: $image->path) }}" alt="Image du post {{ $post->title }}" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </article>
        @empty
            <div class="empty-state">Aucune actualité publiée pour ce club.</div>
        @endforelse
        {{ $posts->links() }}
    </section>

    <div class="card row" style="justify-content: center; gap: 1rem;">
        <a class="btn" href="{{ route('conversations.contact', $club) }}">Contacter le club</a>
        <a class="btn btn--secondary" href="{{ route('clubs.team', $club) }}">Meet the Team</a>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/gallery.js') }}" defer></script>
@endpush
