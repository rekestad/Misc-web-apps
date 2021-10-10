@php
    $uniqueId = uniqid();
    $songs = null;
@endphp
<x-Html.accordion type="wrapper" :accordionId="$uniqueId">
    @foreach($songBooks->sortByDesc('created_at') as $s)
        @php($songs = $s->getSongs())
        <x-Html.accordion type="item">
            <x-Html.accordion
                type="header"
                :accordionId="$uniqueId"
                itemId="{{$loop->index}}">
                <div class="container m-0 p-0 text-start">
                    <div class="row mb-2">
                        <div class="col-12">
                            {{$s->song_book_title}}
                        </div>
                    </div>
                    <div class="row small text-nowrap">
                        <div class="col-12">
                            <div class="d-flex justify-content-start">
                                <div class="pe-2"><i class="fas fa-calendar-alt fa-fw" style="color:lightsteelblue"></i> {{$s->created_at->format('Y-m-d')}}</div>
                                <div class="pe-2"><i class="fas fa-guitar fa-fw" style="color:orange"></i> {{$songs->count()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-Html.accordion>
            <x-Html.accordion type="body" :accordionId="$uniqueId" itemId="{{$loop->index}}">
                <h5>{{$s->song_book_title}}</h5>
                @if(!empty($s->song_book_description))
                    <span class="d-block font-italic">{{$s->song_book_description}}</span>
                @endif
                <hr>
                <table class="table tableFit table-borderless">
                    <tbody>
                    @foreach($songs as $song)
                        <tr>
                            <td class="fit">{{$loop->index+1}}.</td>
                            <td>
                                <div>{{$song->song_title}}</div>
                                @if(!empty($song->song_composer))
                                    <small class="text-secondary">{{$song->song_composer}}</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr>
                <strong>Public URL</strong><br>
                <a
                    class="text-break"
                    target="_blank"
                    href="{{url('showSongBook/'.$s->url_suffix)}}">{{url('showSongBook/'.$s->url_suffix)}}
                </a>
                <hr>
                    <div class="row">
                        <div class="col-6 pe-1">
                            <x-Html.Link.Show
                                linkStyle="block"
                                text="Show"
                                :doShowIcon=true
                                route="{{route('songBooks.show', $s->id)}}"
                            />
                        </div>
                        <div class="col-3 px-1">
                            <x-Html.Link.Edit
                                linkStyle="block"
                                :doShowIcon=true
                                route="{{route('songBooks.edit', $s->id)}}"
                            />
                        </div>
                        <div class="col-3 ps-1">
                            <x-Html.Link.Delete
                                linkStyle="block"
                                :doShowIcon=true
                                route="{{route('songBooks.destroy',$s->id)}}"
                            />
                        </div>
                    </div>
            </x-Html.accordion>
        </x-Html.accordion>
    @endforeach
</x-Html.accordion>
