{{--{{ dd(get_defined_vars()) }}--}}
@php
    $chordLabel = 'Chords (use '.$chordColumnSeparator.' as column separator)';
@endphp
@extends('layouts.app')
@section('content')
    <x-Html.Form.Form :action="$action" :isEdit="$isEdit" title="Song">
        <x-Html.Form.Element.Input
            type="text"
            id="song_title"
            label="Title"
            :isRequired=true
            maxlength="100"
            value="{{$song->song_title ?? null}}"
        />
        <x-Html.Form.Element.Input
            type="text"
            id="song_composer"
            label="Composer"
            :isRequired=false
            maxlength="100"
            value="{{$song->song_composer ?? null}}"
        />
        <x-Html.Form.Element.Select
            id="starting_note"
            label="Starting note (in first chord)"
            :options=$startingNotes
            :isRequired=false
            value="{{$song->starting_note ?? null}}"
        />
        <x-Html.Form.Element.Select
            id="capo_fret_no"
            label="Capo on fret"
            :options=$capoFrets
            :isRequired=false
            value="{{$song->capo_fret_no ?? null}}"
        />
        <x-Html.Form.Element.Textarea
            id="song_lyrics"
            label="Lyrics"
            :isRequired=false
            rows="15"
            :doAllowSpecialChars=true
            value="{{$song->song_lyrics ?? null}}"
        />
        <x-Html.Form.Element.Textarea
            id="song_chords"
            label="{{$chordLabel}}"
            :isRequired=false
            rows="15"
            :doAllowSpecialChars=true
            value="{{$song->song_chords ?? null}}"
        />
    </x-Html.Form.Form>
@endsection
