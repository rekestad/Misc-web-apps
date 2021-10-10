<x-Html.Card.CardComponent
    :cardId=$cardId
    type="wrapper"
    :class=$cardClass
>
    <x-Html.Card.CardComponent
        :cardId=$cardId
        type="header"
        :class=$headerClass
        :isCollapsible=$isCollapsible
    >
        {{ $title }}
    </x-Html.Card.CardComponent>
    <x-Html.Card.CardComponent
        :cardId=$cardId
        type="{{$bodyView}}"
        :class=$bodyClass
        :isCollapsible=$isCollapsible
    >
        {{ $bodySlot }}
    </x-Html.Card.CardComponent>
</x-Html.Card.CardComponent>
