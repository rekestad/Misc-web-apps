{{--{{dd(get_defined_vars())}}--}}
@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" title="Ingredient">
        <x-Html.Form.Element.Input
            type="text"
            id="ingredient_name"
            label="Name"
            :isRequired=true
            value="{{$ingredient->ingredient_name ?? null}}"
        />
        <x-Html.Form.Element.Select
            id="unit_type_id"
            label="Default unit type"
            :options=$unitTypes
            :isRequired=true
            :value=$unitTypeIdSelected
        />
        <x-Html.Form.Element.Select
            id="category_id"
            label="Category"
            :options=$categories
            :isRequired=true
            :value=$categoryIdSelected
        />
    </x-Html.Form.Form>
@endsection
