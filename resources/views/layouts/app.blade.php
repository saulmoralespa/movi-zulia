<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (Auth::check())
    <meta name="user-id" content="{{ Auth::user()->id }}" />
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<nav>
    <div class="nav-wrapper">
        @if(auth()->check())
        <ul id="dropdown1" class="dropdown-content">
            <li>
                <div class="user-view center-align">
                    <a href="#user"><img class="circle avatar-user" src="{{ storageGoogle(Auth::user()->avatar) }}"></a>
                    <a href="#name"><span class="black-text name">{{ Auth::user()->name }}</span></a>
                </div>
            </li>
            <li><a href="{{ route('manager.settings') }}">Ajustes de Cuenta</a></li>
            <li><a href="{{ route('manager.drives') }}">Conductores</a></li>
            <li>
                <a class="waves-effect"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        @endif
        <nav class="purple">
            <div class="nav-wrapper">

                <a href="{{ url('/') }}" class="brand-logo center">
                    <img src="{{ asset('img/logo.png') }}" class="logo" alt="{{ config('app.name') }}">
                </a>
                @if(auth()->check())
                <a href="#" data-target="slide-out" class="sidenav-trigger show-on-medium-and-up"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li>
                        <a class="dropdown-trigger" href="#!" data-target="dropdown1">{{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i></a>
                    </li>
                </ul>
                @endif
            </div>
        </nav>
        @if(auth()->check())
        <ul id="slide-out" class="sidenav">
            <li>
                <div class="user-view">
                    <a href="#user"><img class="circle" src="{{ storageGoogle(Auth::user()->avatar) }}"></a>
                    <a href="#name"><span class="name">{{ Auth::user()->name }}</span></a>
                    <a href="#email"><span class="email"> {{ Auth::user()->email }}</span></a>
                </div>
            </li>
            <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
            <li>

                <a class="waves-effect"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        @endif
    </div>
</nav>
<main class="py-4">
    @yield('content')
</main>
</body>
</html>
