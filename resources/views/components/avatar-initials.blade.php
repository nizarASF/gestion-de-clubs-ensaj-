@props(['name', 'photo' => null, 'small' => false])
@php
    $words = preg_split('/\s+/u', trim((string) $name));
    $initials = mb_strtoupper(mb_substr($words[0] ?? '', 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
@endphp
@if($photo)
    <img class="avatar @if($small) avatar-initials--small @endif" src="{{ Storage::disk('public')->url($photo) }}" alt="Photo de {{ $name }}">
@else
    <span class="avatar-initials @if($small) avatar-initials--small @endif" aria-label="Initiales de {{ $name }}">{{ $initials }}</span>
@endif
