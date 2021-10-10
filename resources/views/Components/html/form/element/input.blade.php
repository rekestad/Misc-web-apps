@if($doDisplayAsInputGroup)<div class="mb-3">@endif
    @if(!empty($label))<label for="{{$id}}" class="form-label">{{$label}}</label>@endif
    <input
        type="{{$type}}"
        class="form-control {{$class}} {{$doDisplaySizeSmall ? 'form-control-sm' : ''}}"
        name="{{$id}}"
        id="{{$id}}"
        value="{!! $value !!}"
        @if(!empty($placeholder))placeholder="{{$placeholder}}"@endif
        @if(!empty($step))step="{{$step}}"@endif
        @if(!empty($minValue))min="{{$minValue}}"@endif
        @if(!empty($maxlength))maxlength="{{$maxlength}}"@endif
        {{$requiredText}}
        @if($doAutoFocus) autofocus @endif
    />
@if($doDisplayAsInputGroup)</div>@endif
