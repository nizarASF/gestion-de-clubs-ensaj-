@extends('layouts.app')
@section('title', 'Posts — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Posts']]" />
    <div class="page-head">
        <h1>Tous les posts</h1>
        <a class="btn" href="{{ route('admin.posts.create') }}">Publier un post</a>
    </div>
    <form class="filters" method="GET" action="{{ route('admin.posts.index') }}">
        <div class="form-row">
            <label for="club_id">Club</label>
            <select id="club_id" name="club_id">
                <option value="">Tous</option>
                @foreach($clubs as $club)
                    <option value="{{ $club->id }}" @selected((string) $clubId === (string) $club->id)>{{ $club->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="">Tous</option>
                @foreach(\App\Enums\Status::cases() as $statusCase)
                    <option value="{{ $statusCase->value }}" @selected($status === $statusCase->value)>{{ $statusCase->label() }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn" type="submit">Filtrer</button>
    </form>
    @forelse($posts as $post)
        <div class="card" style="margin-bottom: .75rem;">
            <div class="row spread">
                <div>
                    <strong>{{ $post->title }}</strong>
                    <div class="muted">{{ $post->club->name }} · par {{ $post->author->full_name }} · {{ $post->created_at?->format('d/m/Y H:i') }}</div>
                </div>
                <div class="row">
                    <x-status-badge :status="$post->status" />
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Supprimer ce post et ses images ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn--small btn--danger" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">Aucun post trouvé.</div>
    @endforelse
    <div class="mt-2">{{ $posts->links() }}</div>
@endsection
