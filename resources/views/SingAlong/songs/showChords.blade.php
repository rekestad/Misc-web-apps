@extends('layouts.appLight')
@section('mainTitle',$title)
@section('faviconUrl','/img/favicon_singAlong.png')
@section('content')
<div class="container-fluid">
    <div class="row mt-2 mb-3">
        <div class="col-12"><h2>{{$title}}</h2></div>
        @if(!empty($startingNote))<div class="col-12 d-block">Starting note: {{$startingNote}}</div>@endif
        @if(!empty($capoFretNo))<div class="col-12 d-block">Capo: {{$capoFretNo}}</div>@endif
    </div>
    <div class="row">
        @foreach($cols as $col)
            <div class="col-sm-{{$divSize}} p-3 border-danger border-top border-end font-monospace text-break">
                {!! nl2br($col) !!}
            </div>
        @endforeach
    </div>
</div>
@endsection
