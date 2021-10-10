@php
    use App\View\Components\Html\DataAttribute;
    use Illuminate\Support\Collection;

    /** @var bool $isEdit */
    /** @var Collection $dishIngredients */

    $ingredient = null;
    $rowId = null;
    $dataAttributeDeleteRow = collect([new DataAttribute('row-id', 'placeholder')]);
    $dataAttributeAddRow = collect([new DataAttribute('table-id', 'placeholder')]);
    $ingredientRowsToShow = ($isEdit && $dishIngredients->count() > 0 ? $dishIngredients->count() : 1);
@endphp
@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" :doWrapInCard=false>
        <x-Html.Card.Card cardId="dishCard" title="Dish">
            <x-slot name="bodySlot">
                <x-Html.Form.Element.Input
                    type="text"
                    id="dish_name"
                    label="Name"
                    maxlength="100"
                    :isRequired=true
                    value="{{$dish->dish_name ?? null}}"
                />
                <x-Html.Form.Element.Textarea
                    id="dish_description"
                    label="Description/Instructions"
                    :isRequired=false
                    maxlength="5000"
                    rows="5"
                    value="{{$dish->dish_description ?? null}}"
                />
                <x-Html.Form.Element.Input
                    type="text"
                    id="url_recipe"
                    label="Link to recipe"
                    maxlength="1000"
                    :isRequired=false
                    value="{{$dish->url_recipe ?? null}}"
                />
                <div>
                    <label class="d-block mb-1">Rating</label>
                    @for ($i = 1; $i <= 5; $i++)
                        <x-Html.Form.Element.Checkbox
                            id="dish_rating-{{$i}}"
                            name="dish_rating"
                            :label=$i
                            :value=$i
                            currentStoredValue="{{$dish->dish_rating ?? null}}"
                            type="radio"
                            :doFormatAsInline=true
                        />
                    @endfor
                </div>
                <div>
                    <label class="d-block mb-1">Difficulty</label>
                    @for ($i = 1; $i <= 5; $i++)
                        <x-Html.Form.Element.Checkbox
                            id="dish_difficulty-{{$i}}"
                            name="dish_difficulty"
                            :label=$i
                            :value=$i
                            currentStoredValue="{{$dish->dish_difficulty ?? null}}"
                            type="radio"
                            :doFormatAsInline=true
                        />
                    @endfor
                </div>
                <x-Html.Form.Element.Input
                    type="number"
                    id="portions"
                    label="Number of portions"
                    step="1"
                    :isRequired=false
                    value="{{$dish->portions ?? null}}"
                />
            </x-slot>
        </x-Html.Card.Card>
        <x-Html.Card.Card cardId="ingredientsCard" title="Choose from existing ingredients">
            <x-slot name="bodySlot">
                <table id="ingredients" class="table order-list table-sm">
                    <thead class="small">
                    <tr>
                        <th>Ingredient</th>
                        <th>Qty</th>
                        <th>Unit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($i = 0; $i < 50; $i++)
                        @php
                            if($isEdit) {
                                $ingredient = $dishIngredients->where('sort_order',$i+1)->first();
                            }
                            $rowId = 'existing-'.$i;
                        @endphp
                        <tr id="{{$rowId}}" @if($i >= $ingredientRowsToShow)class="d-none"@endif>
                            <td id="{{$rowId}}-1" class="w-50">
                                <x-Html.Form.Element.Select
                                    id="ingredients[{{$i}}][ingredient_id]"
                                    :options=$ingredientOptions
                                    :isRequired=false
                                    :doDisplayAsInputGroup=false
                                    :doDisplaySizeSmall=true
                                    class="ingredientSelect"
                                    value="{{$ingredient->id ?? null}}"
                                    nullValueName="select a value"
                                />
                            </td>
                            <td id="{{$rowId}}-2" class="w-20">
                                <x-Html.Form.Element.Input
                                    type="number"
                                    id="ingredients[{{$i}}][quantity]"
                                    step="0.1"
                                    :doDisplaySizeSmall=true
                                    :isRequired=false
                                    value="{{$ingredient->qty ?? 1}}"
                                    minValue="0.1"
                                    :doDisplayAsInputGroup=false
                                />
                            </td>
                            <td id="{{$rowId}}-3" class="w-30">
                                <x-Html.Form.Element.Select
                                    id="ingredients[{{$i}}][unit_type_id]"
                                    :options=$unitTypeOptions
                                    :isRequired=false
                                    :doDisplayAsInputGroup=false
                                    :doDisplaySizeSmall=true
                                    class="unitTypeSelect"
                                    value="{{$ingredient->unit_type_id ?? null}}"
                                    nullValueName="select a value"
                                />
                            </td>
                            <td id="{{$rowId}}-4">
                                @php $dataAttributeDeleteRow->first()->value = $rowId; @endphp
                                <x-Html.Link.Link
                                    linkStyle="iconOnly"
                                    icon="fas fa-trash text-secondary"
                                    linkClassAppend="deleteRowButton"
                                    :dataAttributes=$dataAttributeDeleteRow
                                />
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>
                @php $dataAttributeAddRow->first()->value = 'ingredients'; @endphp
                <x-Html.Link.Link
                    text="Add row +"
                    linkStyle="block"
                    linkClassAppend="border addRowButton"
                    color="light"
                    :doShowIcon=true
                    :dataAttributes=$dataAttributeAddRow
                />
            </x-slot>
        </x-Html.Card.Card>
        <x-Html.Card.Card cardId="newIngredientsCard" title="Create new ingredients">
            <x-slot name="bodySlot">
                <p class="small text-danger"><i class="fas fa-exclamation-triangle"></i> Do not create ingredients that already exist</p>
                <table id="newIngredients" class="table order-list table-sm">
                    <thead class="small">
                    <tr>
                        <th class="text-nowrap">Ingredient name</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th colspan="2">Cat.</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($i = 0; $i < 50; $i++)
                        @php $rowId = 'new-'.$i; @endphp
                        {{-- NEW INGREDIENTS! --}}
                        <tr id="{{$rowId}}" class="d-none">
                            <td style="width:40%">
                                <x-Html.Form.Element.Input
                                    type="text"
                                    id="newIngredients[{{$i}}][ingredient_name]"
                                    maxlength="100"
                                    :doDisplaySizeSmall=true
                                    :doDisplayAsInputGroup=false
                                    :isRequired=false
                                />
                            </td>
                            <td style="width:20%">
                                <x-Html.Form.Element.Input
                                    type="number"
                                    id="newIngredients[{{$i}}][quantity]"
                                    step="0.1"
                                    :doDisplaySizeSmall=true
                                    :isRequired=false
                                    value="1"
                                    minValue="0.1"
                                    :doDisplayAsInputGroup=false
                                />
                            </td>
                            <td style="width:20%">
                                <x-Html.Form.Element.Select
                                    id="newIngredients[{{$i}}][unit_type_id]"
                                    :options=$unitTypeOptions
                                    :isRequired=false
                                    :doDisplayAsInputGroup=false
                                    :doDisplaySizeSmall=true
                                    nullValueName="select a value"
                                />
                            </td>
                            <td style="width:20%">
                                <x-Html.Form.Element.Select
                                    id="newIngredients[{{$i}}][category_id]"
                                    :options=$categoryOptions
                                    :isRequired=false
                                    :doDisplayAsInputGroup=false
                                    :doDisplaySizeSmall=true
                                    nullValueName="select a value"
                                />
                            </td>
                            <td>
                                @php $dataAttributeDeleteRow->first()->value = $rowId; @endphp
                                <x-Html.Link.Link
                                    linkStyle="iconOnly"
                                    icon="fas fa-trash text-secondary"
                                    linkClassAppend="deleteRowButton"
                                    :dataAttributes=$dataAttributeDeleteRow
                                />
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>
                @php $dataAttributeAddRow->first()->value = 'newIngredients'; @endphp
                <x-Html.Link.Link
                    text="Add row +"
                    linkStyle="block"
                    linkClassAppend="border addRowButton"
                    color="light"
                    :doShowIcon=true
                    :dataAttributes=$dataAttributeAddRow
                />
            </x-slot>
        </x-Html.Card.Card>
    </x-Html.Form.Form>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".deleteRowButton").click(function (e) {
                e.preventDefault();
                $('#' + $(this).data('row-id')).remove();
            });

            $(".addRowButton").click(function (e) {
                e.preventDefault();
                $('#'+ $(this).data('table-id')+' tbody tr.d-none:first').removeClass("d-none");
            });

            $(".ingredientSelect").change(function (e) {
                e.preventDefault();

                const rowUnitTypeSelect = $('#'+$(this).closest('tr').prop('id')+'-3').find('select');
                rowUnitTypeSelect.val($(this).find(':selected').data('default-unit'));
                rowUnitTypeSelect.change();
            });
        });
    </script>
@endsection
