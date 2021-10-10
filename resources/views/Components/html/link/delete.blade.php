<form @if(!empty($deleteFormClass))class="{{$deleteFormClass}}" @endif action="{{$route}}" method="post">
    @csrf
    @method('DELETE')
    @if($isBlockBtn)<div class="d-grid gap-2">@endif
        <button type="submit" class="{{$linkClass}}" @if(!$doSuppressConfirmDialog)onclick="return confirm('Are you sure you want to delete?')"@endif data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">
            @if(!empty($icon))<i class="{{$icon}}"></i>@endif
            {{ $text }}
        </button>
        @if($isBlockBtn)</div>@endif
</form>
