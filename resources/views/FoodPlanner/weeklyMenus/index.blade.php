{{--{{ dd(get_defined_vars()) }}--}}
@extends('layouts.app')
@section('content')
    @if($hasCurrentOrNextMenus)
        <x-FoodPlanner.WeeklyMenus.AccordionWrapper :weeklyMenus="$weeklyMenusCurrentNext"/>
    @endif
    @if($hasPreviousMenus)
        <h4 class="mt-4">Previous</h4>
        <x-FoodPlanner.WeeklyMenus.AccordionWrapper :weeklyMenus="$weeklyMenusPrevious"/>
    @endif
@endsection
