@extends('layouts.app')
@section('content')
    @if(!empty($dishes))
        <x-FoodPlanner.Dishes.AccordionWrapper :dishes="$dishes"/>
    @else
        Click "Add new" to create your first dish!
    @endif
@endsection
