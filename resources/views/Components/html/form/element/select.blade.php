{{--{{ dd(get_defined_vars()) }}--}}
<div @if($doDisplayAsInputGroup)class="mb-3"@endif>
    @if(!empty($label))<label for="{{$id}}" class="form-label">{{$label}}</label>@endif
        <div @if($doDisplayAsInputGroup)class="input-group"@endif>
        {{ ($inputGroupBeforeSelect ?? null) }}
        <select
            class="form-select {{$class}} {{$doDisplaySizeSmall ? 'form-select-sm' : ''}}"
            id="{{$id}}"
            name="{{$name ?? $id}}"
            {{$requiredText}}
        >
            @if(!$isRequired)
                <option value="">({{ $nullValueName }})</option>
            @endif
            @foreach($optionGroups->sortBy('sortOrder') as $g)
                @if(!empty($options->where('optionGroup',$g->label)->first()))
                    @if($g->label != 'default')
                        <optgroup label="{{$g->label}}">
                    @endif
                    @foreach($options->where('optionGroup',$g->label) as $o)
                        <option
                            value="{{$o->value}}"
                            @if(!empty($o->style)) style="{{$o->style}}" @endif
                            @if(!empty($value) && $o->value == $value) selected @endif
                            @if(!empty($o->dataAttributes))
                                @foreach($o->dataAttributes as $d)
                                    {{$d->render()}}
                                @endforeach
                            @endif
                        >{{$o->label}}</option>
                    @endforeach
                    @if($g->label != 'default')
                        </optgroup>
                    @endif
                @endif
            @endforeach
        </select>
        {{ ($inputGroupAfterSelect ?? null) }}
    </div>
</div>
