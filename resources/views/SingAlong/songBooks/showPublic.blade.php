{{-- SHOW PUBLIC SONG BOOK --}}
{{--{{ dd(get_defined_vars()) }}--}}
@extends('layouts.appLight')
@section('mainTitle',$title)
@section('faviconUrl','/img/favicon_singAlong.png')
@section('content')
    <!-- Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mx-auto">
                <div class="text-center">
                <div class="mt-3 mb-1 display-3">{{ $title }}</div>
                <p class="text-muted">
                    <em>{{!empty($songBook->song_book_description) ? $songBook->song_book_description : 'The official song book'}}</em>
                </p>
                </div>
                <x-SingAlong.Songs.AccordionWrapper
                    :songs=$songs
                    :isPublicUser=true
                    :doNumberSongs=true
                    :songBookUrl=$songBookUrl />
            </div>
        </div>
        <br>
    </div>
@endsection
