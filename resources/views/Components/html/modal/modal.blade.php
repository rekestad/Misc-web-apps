<div id="{{$id}}" class="modal" tabindex="-1">
    <div class="modal-dialog @if($doVerticallyCenter) modal-dialog-centered @endif">
        <div class="modal-content">
            @if($doUseHeader)
                <div class="modal-header">
                    @if(!empty($title))<h5 class="modal-title">{{$title}}</h5>@endif
                    {{ $headerSlot ?? null }}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif
            <div class="modal-body">
                {{ $bodySlot ?? null }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{--<button type="button" class="btn btn-primary">Ok</button>--}}
                {{ $footerSlot ?? null }}
            </div>
        </div>
    </div>
</div>
