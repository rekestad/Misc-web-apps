@php
    $uniqueId = uniqid();
@endphp
<x-Html.accordion type="wrapper" :accordionId="$uniqueId">
    @foreach($weeklyMenus as $w)
        <x-Html.accordion type="item">
            <x-Html.accordion
                type="header"
                :accordionId="$uniqueId"
                itemId="{{$loop->index}}"
                doStartExpanded="{{$w->is_active}}"
            >
                <div class="container m-0 p-0 text-start">
                    <div class="row text-nowrap">
                        <div class="col-4">
                            Week {{$w->week_no}}
                        </div>
                        <div class="col-5 small">
                            <i class="text-warning far fa-calendar-alt"></i>&nbsp;&nbsp;{{$w->date_week_start}}
                        </div>
                        <div class="col-3 small">
                            <i class="text-warning fas fa-utensils"></i>&nbsp;&nbsp;{{$w->getDishes()->count()}}</div>
                    </div>
                </div>
            </x-Html.accordion>
            <x-Html.accordion
                type="body"
                :accordionId="$uniqueId"
                itemId="{{$loop->index}}"
                doStartExpanded="{{$w->is_active}}"
            >
                <x-FoodPlanner.WeeklyMenus.Show :weeklyMenu="$w"/>
            </x-Html.accordion>
        </x-Html.accordion>
    @endforeach
</x-Html.accordion>
