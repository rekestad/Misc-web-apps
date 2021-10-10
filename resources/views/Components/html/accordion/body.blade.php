<div id="collapse-{{$accordionId}}-{{$itemId}}"
     class="border-primary border-bottom accordion-collapse collapse @if($doStartExpanded) show @endif"
     aria-labelledby="heading-{{$accordionId}}-{{$itemId}}"
     data-bs-parent="#accordion-{{$accordionId}}">
    <div class="accordion-body">
        {{$slot ?? $title}}
    </div>
</div>
