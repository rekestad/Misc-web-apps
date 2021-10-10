@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" title="Group">
        <x-Html.Form.Element.Input
            type="text"
            id="group_name"
            label="Name"
            :doAutoFocus=true
            :isRequired=true
            value="{{$toDoGroup->group_name ?? null}}"
        />
        <x-Html.Form.Element.Checkbox
            id="start_expanded"
            label="Start expanded"
            currentStoredValue="{{$toDoGroup->start_expanded ?? null}}"
        />
        <x-Html.Form.Element.Select
            id="color_bg"
            label="Background color"
            :options="$colorOptions"
            :isRequired=true
            value="{{$toDoGroup->color_bg ?? $colorBgDefault}}">
            <x-slot name="inputGroupAfterSelect">
                <button class="randomButton btn btn-outline-secondary" data-val="color_bg"
                        type="button">
                    <i class="fas fa-random fa-fw"></i></button>
            </x-slot>
        </x-Html.Form.Element.Select>
        <x-Html.Form.Element.Select
            id="color_text"
            label="Text color"
            :options="$colorOptions"
            :isRequired=true
            value="{{$toDoGroup->color_text ?? $colorTxtDefault}}">
            <x-slot name="inputGroupAfterSelect">
                <button class="randomButton btn btn-outline-secondary" data-val="color_text"
                        type="button">
                    <i class="fas fa-random fa-fw"></i></button>
            </x-slot>
        </x-Html.Form.Element.Select>
        <div class="p-3 mb-3 rounded" id="color_example"
             style="
                 background-color: {{ old('color_bg') ?? $toDoGroup->color_bg ?? $colorBgDefault }};
                 color: {{ old('color_text') ?? $toDoGroup->color_text ?? $colorTxtDefault }}"
        >
            {{ old('group_name') ?? $toDoGroup->group_name ?? 'Example' }}
        </div>
        <x-Html.Form.Element.Input
            type="number"
            id="sort_order"
            label="Sort order"
            :isRequired=true
            step="1"
            value="{{ $toDoGroup->sort_order ?? 1 }}"
        />
    </x-Html.Form.Form>
    @if($isEdit)
        <hr>
        <div class="mb-3">
            <x-Html.Link.Delete
                linkStyle="block"
                text="Delete group"
                route="{{route('toDoGroups.destroy',$toDoGroup->id)}}"
            />
        </div>
    @endif
    <script>
        /* Update color example when settings change */
        $(document).ready(function () {
            $("#color_bg").change(function () {
                $("#color_example").css("background-color", $(':selected', this).text());
            });
            $("#color_text").change(function () {
                $("#color_example").css("color", $(':selected', this).text());
            });
            $("#group_name").change(function () {
                $("#color_example").html($(this).val());
            });
            // select random color
            $(".randomButton").bind("click", function () {
                selectRandomOptionInSelectInput($(this).data('val'), {{count($colorOptions)}});
            });
        });
    </script>
@endsection
