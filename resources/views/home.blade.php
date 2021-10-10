{{--{{ dd(get_defined_vars()) }}--}}
@extends('layouts.core')
@section('title','Apps')
@section('firstContent')
    <h1 class="mt-2">Apps</h1>
    <div class="d-grid gap-2 mt-3">
        @foreach($apps as $a)
            <a href="{{ route($a->route_start) }}" class="btn btn-{{ $a->navbar_color }} btn-lg" role="button">
                <i class="fas {{ $a->icon }}"></i> {{ $a->app_name }}
            </a>
        @endforeach
        <a href="{{ route('logout') }}" class="btn btn-secondary btn-lg" role="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Sign out
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
@endsection
