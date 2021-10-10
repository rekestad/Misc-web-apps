{{--{{ dd(get_defined_vars()) }}--}}
@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" title="Item">
        <x-Html.Form.Element.Select
            id="group_id"
            label="Group"
            :options="$toDoGroupOptions"
            :isRequired=true
            value="{{$groupIdSelected}}"
        />
        <x-Html.Form.Element.Input
            type="text"
            id="item_name"
            label="Item name"
            :doAutoFocus=true
            :isRequired=true
            value="{{$toDo->item_name ?? null}}"
        />
        <x-Html.Form.Element.Input
            type="number"
            id="priority_order"
            label="Priority order"
            :isRequired=false
            step="1"
            value="{{$toDo->priority_order ?? null}}"
        />
        <x-Html.Form.Element.Checkbox
            id="is_urgent"
            label="Is urgent"
            currentStoredValue="{{$toDo->is_urgent ?? null}}"
        />
        <x-Html.Form.Element.Input
            type="text"
            id="date_deadline"
            label="Deadline"
            :isRequired=false
            placeholder="YYYY-MM-DD"
            value="{{$toDo->date_deadline ?? null}}"
        />
    </x-Html.Form.Form>
@endsection
