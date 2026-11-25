@props(['items' => []])
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    @foreach($items as $item)
        @if(! $loop->last && isset($item['url']))
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @else
            <span aria-current="page">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
