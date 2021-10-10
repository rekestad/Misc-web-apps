{{--{{ dd(get_defined_vars()) }}--}}
@php
    $doShowExpanded = null;
    $cardId = null;
@endphp
@extends('layouts.app')
@section('content')
    @foreach($toDoGroups->sortBy('sort_order') as $g)
        @php
            $doShowExpanded = ($g->start_expanded === 1 || ($groupIdSelected ?? -1) === $g->id);
            $cardId = $g->id;
        @endphp
        <x-Html.Card.CardComponent
            type="wrapper"
            :cardId=$cardId
        >
            <x-Html.Card.CardComponent
                type="header"
                :cardId=$cardId
                style="background-color: {{$g->color_bg}}; color:{{$g->color_text}}"
                :isCollapsible=false
            >
                <div class="d-flex justify-content-between">
                    <div
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{$cardId}}"
                        role="button"
                        aria-expanded="{{$doShowExpanded ? 'true' : 'false'}}"
                        aria-controls="collapse-{{$cardId}}">
                        {{$g->getTitle()}}
                    </div>
                    <div class="d-flex flex-row">
                        <div class="pe-1">
                            <x-Html.Link.Delete
                                link-style="iconOnly"
                                route="{{route('toDoGroups.deleteChecked', $g->id)}}"
                                icon="fas fa-hand-sparkles fa-lg text-white"
                                :doSuppressConfirmDialog=true
                            />
                        </div>
                        <div class="px-1">
                            <x-Html.Link.Link
                                linkStyle="iconOnly"
                                route="{{route('toDos.create', $g->id)}}"
                                icon="fas fa-plus-circle fa-lg text-white"
                            />
                        </div>
                        <div class="ps-1">
                            <x-Html.Link.Link
                                linkStyle="iconOnly"
                                route="{{route('toDoGroups.edit', $g->id)}}"
                                icon="fas fa-pen fa-lg text-white"
                            />
                        </div>
                    </div>
                </div>
            </x-Html.Card.CardComponent>
            <x-Html.Card.CardComponent
                type="listGroup"
                :cardId=$cardId
                :isCollapsible=true
                :doShowExpanded=$doShowExpanded
            >
                @foreach($g->getItems() as $i)
                    <li id="{{$i->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <input data-id="{{$i->id}}" class="form-check-input me-1 checkBtn" type="checkbox"
                                   value="1" @if($i->is_checked == 1) checked @endif >
                        </div>
                        <div id="item-{{$i->id}}"
                             class="@if($i->is_checked == 1) todo-item-checked @else todo-item @endif ms-2 me-auto">
                            @if($i->is_urgent === 1)
                                <i class="todo-is-urgent fas fa-exclamation-circle"></i>
                            @endif
                            @if(!empty($i->priority_order))
                                <span class="todo-priority-order fw-bold">{{$i->priority_order}}. </span>
                            @endif
                            <a href="{{route('toDos.edit',$i->id)}}">{{$i->item_name}}</a>
                            <i class="todo-check-mark ms-2 fas fa-check text-success"></i>
                            @if(!empty($i->date_deadline))
                                <br><small class="todo-deadline"> Deadline: {{$i->date_deadline}}</small>
                            @endif
                        </div>
                        @if($i->age_in_days > 30)
                            <div class="ms-2 text-nowrap text-secondary" style="font-size: .75em">
                                {{$i->age_in_days}} days
                            </div>
                        @endif
                    </li>
                @endforeach
            </x-Html.Card.CardComponent>
        </x-Html.Card.CardComponent>
    @endforeach
    <script>
        $(document).ready(function () {
            // check row
            $(".checkBtn").click(function (e) {
                const rowId = $(this).data('id');
                const toDoItem = $('#item-' + rowId);

                ajaxCall(
                    "PATCH",
                    "{{ rtrim(route('toDos.checkUncheck',0),0) }}" + rowId,
                    {
                        id: rowId,
                        setIsCheckedTo: this.checked
                    },
                    ajaxResponse_showErrorIfFailed
                );

                if (this.checked) {
                    toDoItem.removeClass('todo-item');
                    toDoItem.addClass('todo-item-checked');
                } else {
                    toDoItem.removeClass('todo-item-checked');
                    toDoItem.addClass('todo-item');
                }
            });
        });
    </script>
@endsection
