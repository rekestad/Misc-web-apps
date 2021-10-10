<div class="mb-3">
    <label for="{{$id}}" class="form-label">{{$label}}</label>
    <textarea
        class="form-control {{$class}}"
        name="{{$id}}"
        id="{{$id}}"
        @if(!empty($rows))rows="{{$rows}}"@endif
        @if(!empty($placeholder))placeholder="{{$placeholder}}"@endif
        @if(!empty($maxlength))maxlength="{{$maxlength}}"@endif
        {{$requiredText}}
    >@if($doAllowSpecialChars){!! $value !!}@else{{$value}}@endif</textarea>
</div>
