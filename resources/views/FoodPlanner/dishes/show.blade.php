@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            Information
        </div>
        <div class="card-body">
            <x-FoodPlanner.Dishes.Show :dish="$dish" :doShowHeading="false"/>
        </div>
    </div>
@endsection
