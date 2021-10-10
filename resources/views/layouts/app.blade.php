@extends('layouts.core')
@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\AdminApp;
    use App\Models\AdminNavGroup;
    use App\Models\AdminNavItem;

    $user          = Auth::user();
    $navItem       = AdminNavItem::where('nav_item_route',\Request::route()->getName())->first();
    $navGroups     = AdminNavGroup::where('app_id',$navItem->app_id)->where('is_active',1)->get();
    $breadcrumbs   = $navItem->getBreadcrumbs();
    $apps          = AdminApp::where([
                            ['is_home_app','=',0],
                            ['is_active','=',1],
                            ['is_development','=',0]
                        ])->orderBy('sort_order')->get();
@endphp
@section('title',($navItem->nav_item_name != 'Home' ? $navItem->nav_item_name : $navItem->app_name))
@section('faviconUrl',$navItem->app_favicon)
@section('navbar')
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-{{$navItem->app_navbar_color}} static-top">
        <div class="container-sm">
            <a class="navbar-brand" href="#">{{$navItem->app_browser_title}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                   @foreach($navGroups as $x)
                        <li class="nav-item">
                            <a class="nav-link {{$x->route_start === $navItem->nav_group_route_start ? 'active' : ''}}" href="{{route($x->route_start)}}">
                                <i class="fas {{$x->icon}} fa-fw"></i>
                                {{$x->nav_group_name}}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
                           aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{ $user->name }}</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fas fa-home fa-fw"></i> Apps
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($apps as $a)
                                <li>
                                    <a class="dropdown-item" href="{{ route($a->route_start) }}">
                                        <i class="fas {{ $a->icon }}"></i> {{ $a->app_name }}
                                    </a>
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> Sign out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection
@section('firstContent')
    {{-- Breadcrumbs --}}
    @if(!empty($breadcrumbs))
        <nav aria-label="breadcrumb" class="breadcrumb-container mt-3">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $b)
                    @if($b->route === $navItem->nav_item_route)
                        <li class="breadcrumb-item active" aria-current="page">{{$b->name}}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{route($b->route)}}">{{$b->name}}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
    {{-- Title --}}
    <h1>{{ $title ?? $navItem->nav_item_name }}</h1>
    {{-- Success message --}}
    @if(session()->get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Error message --}}
    @if(session()->get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session()->get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- "Add" button --}}
    @if(!empty($buttonCreate))
        <div class="d-grid gap-2 mb-3 mt-3">
            <a href="{{route($buttonCreate['route'])}}"
               class="btn btn-primary">{{$buttonCreate['title']}}</a>
        </div>
    @endif
    {{-- Content --}}
    @yield('content')
    @if($doIncludeButtonMenu ?? false)
    <div class="mt-3">
        @foreach($navGroups as $x)
            @if(Auth::user()->name != 'Alexander' && ($x->id === 10 || $x->id === 11))
                @continue
            @else
                @if(!($x->route_start === $navItem->nav_group_route_start))
                    <x-Html.Link.Link
                        linkStyle="block"
                        route="{{route($x->route_start)}}"
                        icon="fas {{$x->icon}}"
                        color="secondary"
                        link-class-append="mb-3 btn-lg"
                        text="{{$x->nav_group_name}}"
                    />
                @endif
            @endif
        @endforeach
    @endif
    </div>
    <x-Html.Modal.Modal :doUseHeader=false :doVerticallyCenter=true id="errorModal">
        <x-slot name="bodySlot">
            <p id="errorModalBody" class="mt-2"></p>
        </x-slot>
    </x-Html.Modal.Modal>
    <x-Html.Modal.Modal :doUseHeader=true :doVerticallyCenter=true id="infoModal">
        <x-slot name="headerSlot">
            <h5 class="modal-title" id="infoModalHeader"></h5>
        </x-slot>
        <x-slot name="bodySlot">
            <p id="infoModalBody"></p>
        </x-slot>
    </x-Html.Modal.Modal>
@endsection
