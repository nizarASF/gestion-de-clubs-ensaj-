@extends('layouts.app')
@section('title', 'Mes posts — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre', 'url' => route('manage.dashboard')], ['label' => 'Posts']]" />
    <div class="page-head">
        <h1>Posts de mon club</h1>
        <a class="btn" href="{{ route('manage.posts.create') }}">Nouveau post</a>
    </div>
    @forelse($posts as $post)
        <div class="card" style="margin-bottom: .75rem;">
            <div class="row spread">
                <div>
                    <strong>{{ $post->title }}</strong>
                    <div class="muted">{{ $post->created_at?->format('d/m/Y H:i') }} · {{ $post->images->count() }} image(s)</div>
                    @if($post->rejection_reason)<p class="form-error">Motif du refus : {{ $post->rejection_reason }}</p>@endif
                </div>
                <div class="row">
                    <x-status-badge :status="$post->status" />
                    <a class="btn btn--small btn--secondary" href="{{ route('manage.posts.edit', $post) }}">Modifier</a>
                    @if(in_array($post->status, [\App\Enums\Status::Draft, \App\Enums\Status::Rejected], true))
                        <form method="POST" action="{{ route('manage.posts.submit', $post) }}">
                            @csrf
                            <button class="btn btn--small btn--accent" type="submit">Soumettre</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">Aucun post pour le moment.</div>
    @endforelse
    <div class="mt-2">{{ $posts->links() }}</div>
@endsection
