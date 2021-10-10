<div
    id="cardBody-{{$cardId}}"
    class="card-body {{$class ?? null}}"
    @if(!empty($style)) style="{{$style}}" @endif
>
    {{ $slot }}
</div>
