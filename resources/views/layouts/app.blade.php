<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        let apiUrl = 'http://localhost:8000/api/'; // Local
        // let apiUrl = 'urlServidor'; // server
        let wAuthToken = '<?php echo (Auth::check() ? Auth::user()->remember_token : ""); ?>';
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ strtoupper(config('app.name', 'Laravel')) }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="#" class="nav-link" onClick="toggleMenu()">
                                    Menu
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" href="#" >
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                            <li class="nav-item" >
                                <a class="nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    Sair
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @guest
            @yield('content')
        @else
            <div class="h-100" id="wb-side-menu" >
                <div class="bg-dark py-3" id="sidebar" role="navigation">
                    <h4 class="text-white text-center" >
                        {{ strtoupper(Auth::user()->name) }}
                    </h4>
                    <div class="list-group list-group-flush py-2">
                        <a class="list-group-item bg-none text-decoration-none text-white" href="{{ url('/') }}">
                            Ínicio
                        </a>
                        <a class="list-group-item bg-none text-decoration-none text-white" href="{{ route('clients') }}">
                            Clientes
                        </a>
                        <a class="list-group-item bg-none text-decoration-none text-white" href="{{ route('products')}}" >
                            Produtos
                        </a>
                        <a class="list-group-item bg-none text-decoration-none text-white" href="{{ route('sales.index') }}" >
                            Vendas
                        </a>
                    </div>
                </div>
                <div class=" container py-5">
                    @yield('content')
                </div>
            </div>
        @endguest
    </div>

    <script>
        function toggleMenu(){
            let menu = document.getElementById('wb-side-menu');
            menu.classList.toggle('active');
        }
    </script>
</body>
</html>
