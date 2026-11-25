@props(['status'])
@php
    // Accepte aussi bien un enum Status qu'une chaîne issue de la base.
    $value = $status instanceof \App\Enums\Status ? $status : \App\Enums\Status::tryFrom((string) $status);
@endphp
@if($value)
    <span class="badge {{ $value->badgeClass() }}">{{ $value->label() }}</span>
@endif
