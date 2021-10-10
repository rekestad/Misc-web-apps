@php
    $weeklyMenus = $dish->getWeeklyMenus();
@endphp
@if($doShowHeading)
    <h5 class="card-title">{{$dish->dish_name}}</h5>
    <hr>
@endif
@if($dish->getIngredients()->count() > 0)
    <p><strong>Ingredients</strong>
        @if(!empty($dish->portions))
            <i> ({{$dish->portions}} portions)</i>
        @endif
        <br>
        @foreach($dish->getIngredients() as $ing)
            {{$ing->qty.' '.$ing->unit.' '.$ing->name}}<br>
    @endforeach
    <hr>
@endif
@if(!empty($dish->dish_description) && trim($dish->dish_description) != '')
    <p>{!! nl2br($dish->dish_description) !!}</p>
    <hr>
@endif
<div class="row mb-2">
    <div class="col-6">
        <strong>Rating</strong><br>
        @for ($i = 1; $i <= 5; $i++)
            <i class="fas fa-star"
               style="color:{{ $i <= $dish->dish_rating ? 'orange' : 'lightgrey' }}"></i>
        @endfor
    </div>
    <div class="col-6">
        <strong>Difficulty</strong><br>
        @for ($i = 1; $i <= 5; $i++)
            <i class="fas fa-clock"
               style="color:{{ $i <= $dish->dish_difficulty ? 'CornflowerBlue' : 'lightgrey' }}"></i>
        @endfor
    </div>
</div>
<div class="row">
    <div class="col-6">
        <strong>On the menu</strong><br>
        {{$weeklyMenus->count()}} {{$weeklyMenus->count() == 1 ? 'time' : 'times'}}
    </div>
    <div class="col-6">
        <strong>Last time</strong><br>
        {{$weeklyMenus->max('date_week_start') ?? '-'}}
    </div>
</div>
<hr>
<div class="row">
    <div class="col-6 pe-1">
        @if(!empty($dish->url_recipe))
            <x-Html.Link.Link
                linkStyle="block"
                color="primary"
                icon="fas fa-external-link-alt"
                text="Recipe"
                route="{{$dish->url_recipe}}"
                :doOpenInNewWindow=true
            />
        @endif
    </div>
    <div class="col-3 px-1">
        <x-Html.Link.Edit
            linkStyle="block"
            :doShowIcon=true
            route="{{route('dishes.edit', $dish->id)}}"
        />
    </div>
    <div class="col-3 ps-1">
        <x-Html.Link.Delete
            linkStyle="block"
            :doShowIcon=true
            route="{{route('dishes.destroy',$dish->id)}}"
        />
    </div>
</div>

