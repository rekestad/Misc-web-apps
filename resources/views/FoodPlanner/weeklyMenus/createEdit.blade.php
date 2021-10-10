{{--{{ dd(get_defined_vars()) }}--}}
@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" title="Weekly menu">
        <x-Html.Form.Element.Select
            id="date_week_start"
            label="Week"
            :options="$weeks"
            :isRequired=true
            :value="$weeksSelected"
        />
        <div class="mb-3">
            <small class="form-text text-muted">Lists only weeks with no existing menu</small>
        </div>
        {{--<div class="mt-3 mb-3">
        <x-Html.Link.Link
            linkStyle="block"
            color="outline-secondary"
            icon="fas fa-random fa-fw"
            text="Surprise me",
            route=""
        />
        </div>--}}
        @foreach(array_filter(util_getWeekDayNames()) as $wd)
            <x-Html.Form.Element.Select
                id="dish-{{ $loop->index }}"
                name="dishes[{{ $loop->index }}][dish_id]"
                :label="$wd"
                :options="$dishes"
                :isRequired=false
                value="{{ ($selectedDishes[$loop->index]->id ?? null) }}"
            >
                <x-slot name="inputGroupAfterSelect">
                    <button class="emptyButton btn btn-outline-secondary" data-val="dish-{{ $loop->index }}"
                            type="button">
                        <i class="fas fa-times fa-fw"></i></button>
                    <button class="randomButton btn btn-outline-secondary" data-val="dish-{{ $loop->index }}"
                            type="button">
                        <i class="fas fa-random fa-fw"></i></button>
                    <input type="hidden" name="dishes[{{$loop->index}}][day_of_week]" value="{{$loop->index+1}}"/>
                </x-slot>
            </x-Html.Form.Element.Select>
        @endforeach
    </x-Html.Form.Form>
    <script type="text/javascript">
        jQuery(function () {
            // select dish "(none)"
            $(".emptyButton").bind("click", function () {
                $('#' + $(this).data('val')).val('').trigger('change');
            });

            // select random dish
            $(".randomButton").bind("click", function () {
                selectRandomOptionInSelectInput($(this).data('val'), {{$dishes->count()}});
            });
        });
    </script>
@endsection
