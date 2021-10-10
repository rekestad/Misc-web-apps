<h2 class="accordion-header" id="heading-{{$accordionId}}-{{$itemId}}">
    <button class="accordion-button accordion-button-ow @if(!$doStartExpanded) collapsed @endif" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapse-{{$accordionId}}-{{$itemId}}" aria-expanded="false"
            aria-controls="collapse-{{$accordionId}}-{{$itemId}}">
        {{$slot}}
    </button>
</h2>
