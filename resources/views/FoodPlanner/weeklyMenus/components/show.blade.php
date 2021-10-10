@php
    $dishes = $weeklyMenu->getDishes();
@endphp
@if($dishes->count() > 0)
    <table class="table table-borderless tableFit">
        <tbody>
        @foreach ($weeklyMenu->getDishes() as $d)
            <tr>
                <td class="fit"><strong>{{$weekDayNames[$d->day_of_week]}}</strong></td>
                <td><a href="{{route('dishes.show',$d->dish_id)}}">{{$d->dish_name}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    No food this week <i class="far fa-frown-open"></i>
@endif
<hr>
<div class="row">
    <div class="col-6 pe-1">
        <x-Html.Link.Link
            linkStyle="block"
            color="primary"
            text="List"
            icon="fas fa-shopping-cart"
            route="{{route('shoppingLists.show', $weeklyMenu->getShoppingListId())}}"
        />
    </div>
    <div class="col-3 px-1">
        <x-Html.Link.Edit
            linkStyle="block"
            :doShowIcon=true
            route="{{route('weeklyMenus.edit', $weeklyMenu->id)}}"
        />
    </div>
    <div class="col-3 ps-1">
        <x-Html.Link.Delete
            linkStyle="block"
            :doShowIcon=true
            route="{{route('weeklyMenus.destroy',$weeklyMenu->id)}}"
        />
    </div>
</div>
