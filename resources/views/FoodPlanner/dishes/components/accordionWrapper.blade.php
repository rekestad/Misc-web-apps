@php
    $uniqueId = uniqid();
@endphp
<x-Html.accordion type="wrapper" :accordionId="$uniqueId">
    @foreach($dishes as $d)
        <x-Html.accordion type="item">
            <x-Html.accordion
                type="header"
                :accordionId="$uniqueId"
                itemId="{{$loop->index}}">
                <div class="container m-0 p-0 text-start">
                    <div class="row mb-2">
                        <div class="col-12">
                            {{$d->dish_name}}
                        </div>
                    </div>
                    <div class="row small text-nowrap">
                        <div class="col-12">
                            <div class="d-flex justify-content-start">
                                @if(!empty($d->dish_rating))<div class="pe-2"><i class="fas fa-star fa-fw" style="color:orange"></i> {{$d->dish_rating}}</div>@endif
                                @if(!empty($d->dish_difficulty))<div class="pe-2"><i class="fas fa-clock fa-fw" style="color:CornflowerBlue"></i> {{$d->dish_difficulty}}</div>@endif
                                <div class="pe-2"><i class="fas fa-carrot fa-fw" style="color:DarkSeaGreen"></i> {{$d->getIngredients()->count()}}</div>
                                <div class="pe-2"><i class="fas fa-calendar-alt fa-fw" style="color:lightsteelblue"></i> {{$d->getWeeklyMenus()->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-Html.accordion>
            <x-Html.accordion type="body" :accordionId="$uniqueId" itemId="{{$loop->index}}">
                <x-FoodPlanner.Dishes.Show :dish="$d" :isPublicUser=true :doShowHeading=true />
            </x-Html.accordion>
        </x-Html.accordion>
    @endforeach
</x-Html.accordion>

