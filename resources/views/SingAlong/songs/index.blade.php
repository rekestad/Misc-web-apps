@extends('layouts.app')
@section('content')
    <x-SingAlong.Songs.AccordionWrapper :songs=$songs :isPublicUser=false />
@endsection
