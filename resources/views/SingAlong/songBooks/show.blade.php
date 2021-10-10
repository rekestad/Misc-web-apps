@extends('layouts.app')
@section('content')
    @if(!empty($songBook->song_book_description))
        <p>{{$songBook->song_book_description}}</p>
    @endif
    <x-SingAlong.Songs.AccordionWrapper :songs=$songs :isPublicUser=false :doNumberSongs=true />
@endsection
