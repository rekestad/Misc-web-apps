@extends('layouts.app')
@section('content')
    <p>It's so nice to have you back, {{ $user->name }}.</p>
@if(!empty($currentMenu))
    <x-Html.Card.Card
        cardId="card-1"
        title="This week's menu ({{$currentMenu->date_week_start}})"
        cardClass="border border-success"
        headerClass="bg-success text-white"
    >
        <x-slot name="bodySlot">
            <x-FoodPlanner.WeeklyMenus.Show :weeklyMenu="$currentMenu"/>
        </x-slot>
    </x-Html.Card.Card>
@endif
@if(!empty($nextMenu))
    <x-Html.Card.Card
        cardId="card-2"
        title="Next week's menu ({{$nextMenu->date_week_start}})"
        cardClass="border border-secondary"
        headerClass="bg-secondary text-white"
    >
        <x-slot name="bodySlot">
            <x-FoodPlanner.WeeklyMenus.Show :weeklyMenu="$nextMenu"/>
        </x-slot>
    </x-Html.Card.Card>
@endif
@if(!empty($dishesTopList))
    <x-Html.Card.Card
        cardId="card-3"
        title="Your top dishes"
        cardClass="border border-info"
        headerClass="bg-info text-white"
        :isListGroup=true
    >
        <x-slot name="bodySlot">
            @foreach($dishesTopList as $d)
                <x-Html.Card.CardComponent cardId="card-3" type="listGroupItem">
                    <strong>{{$loop->index+1}}. Eaten {{$d->total_count}} {{$d->total_count > 1 ? 'times' : 'time'}}</strong><br>{!! $d->dish_name !!}
                </x-Html.Card.CardComponent>
            @endforeach
        </x-slot>
    </x-Html.Card.Card>
@endif
@if(!empty($dishesNeverEaten))
    <x-Html.Card.Card
        cardId="card-4"
        title="Wondering what to eat?"
        cardClass="border border-secondary"
        headerClass="bg-secondary text-white"
        :isListGroup=true
    >
        <x-slot name="bodySlot">
            @foreach($dishesNeverEaten as $d)
                <x-Html.Card.CardComponent cardId="card-4" type="listGroupItem">
                    <strong>Eaten 0 times</strong><br>{!! $d->dish_name !!}
                </x-Html.Card.CardComponent>
            @endforeach
        </x-slot>
    </x-Html.Card.Card>
@endif
@endsection
