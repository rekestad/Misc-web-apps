@php
    $uniqueId = uniqid();
@endphp
<x-Html.accordion type="wrapper" :accordionId="$uniqueId">
    @foreach($songs as $s)
        <x-Html.accordion type="item">
            <x-Html.accordion
                type="header"
                :accordionId="$uniqueId"
                itemId="{{$loop->index}}">
                <div class="container m-0 p-0 text-start">
                    <div class="row mb-1">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pe-2">
                                    @if($doNumberSongs){{$loop->index+1}}. @endif{{$s->song_title}}
                                    @if(!empty($s->song_composer))
                                        <small class="mt-1 d-block text-secondary">{{$s->song_composer}}</small>
                                    @endif
                                </div>
                                @if(!$isPublicUser)
                                    <div class="pe-2 text-nowrap">
                                        <i class="fas fa-microphone fa-fw song-icon-{{ !empty($s->song_lyrics) ? 'filled' : 'empty' }}"></i>
                                        <i class="fas fa-guitar fa-fw song-icon-{{ !empty($s->song_chords) ? 'filled' : 'empty' }}"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-Html.accordion>
            <x-Html.accordion type="body" :accordionId="$uniqueId" itemId="{{$loop->index}}">
                <div>
                    <h5 class="card-title">{{$s->song_title}}</h5>
                    @if(!empty($s->song_composer))
                        <h6>{{$s->song_composer}}</h6>
                    @endif
                    @if(!$isPublicUser)
                        @if(!empty($s->starting_note))
                            <small class="text-secondary d-block">
                                Starting note: {{$s->starting_note}}
                            </small>
                        @endif
                        @if(!empty($s->capo_fret_no))
                            <small class="text-secondary d-block">
                                Capo: {{$s->capo_fret_no}}
                            </small>
                        @endif
                    @endif
                </div>
                <hr>
                {!! nl2br($s->song_lyrics) !!}
                @if(!empty($s->song_chords) || !$isPublicUser)
                    <hr>
                    <div class="row">
                        <div class="col-6 pe-1">
                            @if(!empty($s->song_chords))
                                <x-Html.Link.Link
                                    linkStyle="block"
                                    color="primary"
                                    icon="fas fa-external-link-alt"
                                    text="Chords"
                                    route="{{ (!$isPublicUser ?
                                            route('showChords', $s->id ) :
                                            route('showPublicChords', [$songBookUrl, $loop->index+1]))}}"
                                    :doOpenInNewWindow=true
                                />
                            @endif
                        </div>
                        <div class="col-3 px-1">
                            @if(!$isPublicUser)
                                <x-Html.Link.Edit
                                    linkStyle="block"
                                    :doShowIcon=true
                                    route="{{route('songs.edit', $s->id)}}"
                                />
                            @endif
                        </div>
                        <div class="col-3 ps-1">
                            @if(!$isPublicUser)
                                <x-Html.Link.Delete
                                    linkStyle="block"
                                    :doShowIcon=true
                                    route="{{route('songs.destroy',$s->id)}}"
                                />
                            @endif
                        </div>
                    </div>
                @else
                    <div class="mb-4"></div>
                @endif
            </x-Html.accordion>
        </x-Html.accordion>
    @endforeach
</x-Html.accordion>
