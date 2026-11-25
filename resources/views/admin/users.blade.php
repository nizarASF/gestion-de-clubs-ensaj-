@extends('layouts.app')
@section('title', 'Utilisateurs — Ensaj Clubs')
@section('content')
    <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Utilisateurs']]" />
    <div class="page-head"><h1>Utilisateurs</h1></div>

    <form class="filters" method="GET" action="{{ route('admin.users.index') }}">
        <div class="form-row">
            <label for="q">Rechercher</label>
            <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Nom ou email">
        </div>
        <div class="form-row">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="">Tous</option>
                @foreach(\App\Enums\Role::cases() as $roleCase)
                    <option value="{{ $roleCase->value }}" @selected($role === $roleCase->value)>{{ $roleCase->label() }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn" type="submit">Filtrer</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Utilisateur</th><th>Filière</th><th>Rôle actuel</th><th>Changer le rôle</th></tr></thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->full_name }}</strong><br><small>{{ $user->email }}</small></td>
                    <td>{{ $user->field_of_study?->label() }}</td>
                    <td>{{ $user->role->label() }}@if($user->club) <br><small>{{ $user->club->name }}</small>@endif</td>
                    <td>
                        @if($user->id === auth()->id())
                            <span class="muted">Vous-même</span>
                        @else
                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="row">
                                @csrf
                                @method('PATCH')
                                <select name="role" aria-label="Nouveau rôle" style="max-width: 150px;">
                                    @foreach([\App\Enums\Role::Student, \App\Enums\Role::ClubMember, \App\Enums\Role::Bde] as $roleCase)
                                        <option value="{{ $roleCase->value }}" @selected($user->role === $roleCase)>{{ $roleCase->label() }}</option>
                                    @endforeach
                                </select>
                                <select name="club_id" aria-label="Club (pour les membres)" style="max-width: 170px;">
                                    <option value="">— Club —</option>
                                    @foreach($clubs as $club)
                                        <option value="{{ $club->id }}" @selected($user->club_id === $club->id)>{{ $club->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn--small" type="submit">Appliquer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty-state">Aucun utilisateur trouvé.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-2">{{ $users->links() }}</div>
@endsection
