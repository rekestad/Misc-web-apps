@php
    /** @var int $displayIsChecked */
    use App\View\Components\Html\Table\TableRowLink;

    $icon = null;
    $btnText = null;

    if($displayIsChecked == 0) {
        $icon = 'fas fa-check-circle fa-2x text-success';
        $btnText = 'checked';
    } else {
        $icon = 'fas fa-arrow-alt-circle-left fa-2x text-warning';
        $btnText = 'unchecked';
    }

    $tableRowLinks = collect([
        new TableRowLink(
            null,
            1,
            false,
            false,
            false,
            $icon,
            'checkBtn'
        )
    ]);
@endphp
@extends('layouts.app')
@section('content')
    <h3>Week {{$weeklyMenu->week_no}} ({{$weeklyMenu->date_week_start}})</h3>
    <x-Html.Link.Link
        linkStyle="block"
        text="Show {{$btnText}} items"
        linkClassAppend="border mt-3"
        color="light"
        route="{{route('shoppingLists.show', [$shoppingList->id, !$displayIsChecked])}}"
    />
    @if(count($shoppingListRows) > 0)
        <x-Html.Table.Table
            :tableRows=$shoppingListRows
            :tableHeaderRow=null
            :tableRowLinks=$tableRowLinks
            :isSearchable=true
        />
    @else
        <p class="m-3">
            @if($displayIsChecked == 0)
                No more items to check. Well done <i class="far fa-thumbs-up"></i>
            @else
                No items checked so far.
            @endif
        </p>
    @endif
    <x-Html.Form.Form action="{{route('shoppingListRows.store', $shoppingList->id)}}" :isEdit=false title="Add items">
            @for ($i = 1; $i <= 3; $i++)
                <div class="row mb-2 g-2">
                    <div class="col-3">
                        <x-Html.Form.Element.Input
                            type="number"
                            id="item[{{$i}}][quantity]"
                            value="1"
                            step="0.1"
                            minValue="0.1"
                            :doDisplayAsInputGroup=false
                        />
                    </div>
                    <div class="col-9">
                        <x-Html.Form.Element.Input
                            type="text"
                            id="item[{{$i}}][item_name]"
                            :doDisplayAsInputGroup=false
                        />
                    </div>
                </div>
            @endfor
    </x-Html.Form.Form>
    <x-Html.Modal.Modal :doUseHeader=true :doVerticallyCenter=true id="dishModal">
        <x-slot name="headerSlot">
            <h5>Hello</h5>
        </x-slot>
        <x-slot name="bodySlot">
            <p>There</p>
        </x-slot>
    </x-Html.Modal.Modal>
    <script>
        $(document).ready(function() {
            // check row
            $(".checkBtn").click(function(e) {
                e.preventDefault();
                const tableRow = $(this).closest('tr');
                const setIsCheckedTo = $(this).data('set_is_checked_to');
                const rowId = $(this).data('id');
                ajaxCall(
                    "PATCH",
                    "{{ rtrim(route('shoppingListRows.update',0),0) }}"+rowId,
                    {setIsCheckedTo: setIsCheckedTo},
                    ajaxResponse_showErrorIfFailed
                );

                tableRow.fadeOut(300);
            });

            // show dishes for ingredient
            $(".showDishesBtn").click(function(e) {
                e.preventDefault();
                const rowId = $(this).data('id');
                console.log(rowId)
                ajaxCall(
                    "GET",
                    "{{ rtrim(route('shoppingListRows.showDishes',0),0) }}"+rowId,
                    null,
                    showInfoModal
                );
            });
        });
    </script>
@endsection
