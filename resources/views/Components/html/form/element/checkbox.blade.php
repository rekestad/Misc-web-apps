{{--{{ dd(get_defined_vars()) }}--}}
<div class="form-check mb-3 @if($doFormatAsInline) form-check-inline m-1 @endif">
    <input
        class="form-check-input {{$class}}"
        type="{{$type}}"
        value="{{$value}}"
        id="{{$id}}"
        name="{{$name ?? $id}}"
        {{$checked}}
    >
    <label class="form-check-label" for="{{$id}}">
        {{$label}}
    </label>
</div>
