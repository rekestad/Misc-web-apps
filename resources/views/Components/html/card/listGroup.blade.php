<ul
    @if($isCollapsible) id="collapse-{{$cardId}}" @endif
    class="list-group list-group-flush {{$class}} @if($isCollapsible) collapse @endif @if($doShowExpanded) show @endif"
>
    {{ $slot }}
</ul>
