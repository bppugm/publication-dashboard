<!doctype html>
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

    <!-- MDI Font -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="d-flex flex-column" style="min-height: 100vh !important">
        <div>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm py-0">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Masuk') }}</a>
                            </li>
                            @endif

                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Daftar') }}</a>
                            </li>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-expand-md bg-primary shadow-sm py-0">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto" style="height: 64px">
                            <a class="navbar-brand d-flex py-0 h-100" href="{{ url('/') }}">
                                <img class="my-auto" src="/images/logo-bpp-horizontal.svg" alt="logo" />
                            </a>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto" style="height: 64px">
                            @auth
                            <li
                                class="nav-item nav-custom px-2 {{ request()->is('dashboard') ? 'active-nav-custom' : '' }}">
                                <a class="nav-link d-flex h-100" href="{{ route('dashboard.index') }}">
                                    <span class="my-auto">
                                        DASHBOARD
                                    </span>
                                </a>
                            </li>
                            <li
                                class="nav-item nav-custom px-2 {{ request()->is('data', 'category') ? 'active-nav-custom' : '' }}">
                                <a class="nav-link d-flex h-100" href="{{ route('data.index') }}">
                                    <span class="my-auto">
                                        DATA
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item nav-custom px-2 {{ request()->is('user') ? 'active-nav-custom' : '' }}">
                                <a class="nav-link d-flex h-100" href="{{ route('user.index') }}">
                                    <span class="my-auto">
                                        USER
                                    </span>
                                </a>
                            </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <main>
            @yield('content')
        </main>

        <footer class="d-md-flex bg-light mt-auto">
            <div class="container d-flex justify-content-between">
                <ul class="navbar-nav me-auto d-flex flex-row">
                    <i class="mdi mdi-email"></i>
                    <a href="mailto:bpp@ugm.ac.id" class="text-dark ms-2">
                        bpp@ugm.ac.id
                    </a>
                </ul>
                <ul class="d-none d-md-flex navbar-nav ms-auto">
                    Dikembangkan oleh Badan Penerbit dan Publikasi Universitas Gadjah Mada
                </ul>
            </div>
        </footer>
    </div>
</body>

</html>
