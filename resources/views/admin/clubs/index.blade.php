@extends('layouts.app')
@section('title', 'Clubs — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Clubs']]" />
    <div class="page-head">
        <h1>Clubs</h1>
        <a class="btn" href="{{ route('admin.clubs.create') }}">Nouveau club</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Club</th><th>Slug</th><th>État</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($clubs as $club)
                <tr>
                    <td><strong>{{ $club->name }}</strong><br><small>{{ $club->short_description }}</small></td>
                    <td>{{ $club->slug }}</td>
                    <td>@if($club->is_active)<span class="badge badge--approved">Actif</span>@else<span class="badge badge--draft">Désactivé</span>@endif</td>
                    <td>
                        @if($club->is_active)
                            <a class="btn btn--small btn--secondary" href="{{ route('clubs.show', $club) }}">Voir</a>
                        @endif
                        <a class="btn btn--small btn--secondary" href="{{ route('admin.clubs.edit', $club) }}">Modifier</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-2">{{ $clubs->links() }}</div>
@endsection
