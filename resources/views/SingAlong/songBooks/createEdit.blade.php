{{--{{ dd(get_defined_vars()) }}--}}
@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" title="Song book" :doWrapInCard=false>
        <x-Html.Card.Card cardId="card-1" title="Song book">
            <x-slot name="bodySlot">
                <x-Html.Form.Element.Input
                    type="text"
                    id="song_book_title"
                    label="Title"
                    :isRequired=true
                    maxlength="100"
                    value="{{$songBook->song_book_title ?? null}}"
                />
                <x-Html.Form.Element.Input
                    type="text"
                    id="url_suffix"
                    label="Public URL suffix"
                    :isRequired=true
                    maxlength="100"
                    value="{{$songBook->url_suffix ?? null}}"
                />
                <x-Html.Form.Element.Textarea
                    id="song_book_description"
                    label="Description"
                    rows="4"
                    :isRequired=false
                    value="{{$songBook->song_book_description ?? null}}"
                />
            </x-slot>
        </x-Html.Card.Card>
        <x-Html.Card.Card cardId="card-2" title="Songs">
            <x-slot name="bodySlot">
                <table id="songs" class="table order-list table-sm">
                    <thead class="small tableFit">
                    <tr>
                        <th>#</th>
                        <th>Song</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($i = 1; $i <= 20; $i++)
                        <tr id="trow-{{$i}}">
                            <td class="fit align-middle">
                                {{$i}}
                            </td>
                            <td class="w-100">
                                <x-Html.Form.Element.Select
                                    id="song[{{$i}}][song_id]"
                                    :options=$songOptions
                                    :isRequired=false
                                    :doDisplayAsInputGroup=false
                                    value="{{ ($isEdit ? ($songBookSongs->where('sort_order',$i)->first()->id ?? null) : null)}}"
                                />
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </x-slot>
        </x-Html.Card.Card>
    </x-Html.Form.Form>
@endsection
