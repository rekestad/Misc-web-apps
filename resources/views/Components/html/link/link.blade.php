@if($isBlockBtn)<div class="d-grid gap-2">@endif
<a
    id="{{$id}}"
    href="{{$route ?? '#'}}"
    class="{{$linkClass}}"
    @if($doOpenInNewWindow)target="_blank"@endif
    @if(!empty($dataAttributes))
        @foreach($dataAttributes as $d)
            {{$d->render()}}
        @endforeach
    @endif
>
    @if(!empty($icon))<i class="{{$icon}}"></i>@endif
    {{ $text }}
</a>
@if($isBlockBtn)</div>@endif
