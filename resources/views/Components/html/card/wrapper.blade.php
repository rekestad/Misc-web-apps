<div
    id="card-{{$cardId}}"
    class="card mb-3 {{$class ?? null}}"
    @isset($style)style="{{$style}}"@endisset
>
    {{ $slot }}
</div>
