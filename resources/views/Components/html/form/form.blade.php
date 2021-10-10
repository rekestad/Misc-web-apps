{{--{{ dd(get_defined_vars()) }}--}}
{{-- ERRORS --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div><br/>
@endif
{{-- FORM --}}
<form method="post" action="{{ $action }}">
    @csrf
    @if($isEdit)
        @method('PATCH')
    @endif
    @if($doWrapInCard)
        <x-Html.Card.Card cardId="form-{{uniqid()}}" :title=$title >
            <x-slot name="bodySlot">
                {{ $slot }}
            </x-slot>
        </x-Html.Card.Card>
    @else
        {{ $slot }}
    @endif
    <div class="row mb-3">
        <div class="col-6 d-grid gap-1 pe-1">
            <button type="submit" class="btn btn-primary">{{$submitBtnTxt ?? 'Save'}}</button>
        </div>
        <div class="col-6 d-grid gap-1 ps-1">
            <a href="{{url()->previous()}}" class="btn btn-outline-primary">Cancel</a>
        </div>
    </div>
</form>
{{ ($AfterForm ?? null) }}
