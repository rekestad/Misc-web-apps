<div
    id="cardHeader-{{$cardId}}"
    class="card-header {{$class ?? null}}"
    @isset($style) style="{{$style}}" @endisset
    @if($isCollapsible)
    data-bs-toggle="collapse"
    data-bs-target="#collapse-{{$cardId}}"
    role="button"
    aria-expanded="false"
    aria-controls="collapse-{{$cardId}}"
    @endif
>
    {{ $slot }}
</div>
