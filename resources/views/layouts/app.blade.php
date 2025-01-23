@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Korepetycje') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script>
        // Funkcja do zmiany rozmiaru czcionki
        function resizeText(action) {
            const rootElement = document.documentElement; // Pobierz element <html>
            const currentSize = parseFloat(getComputedStyle(rootElement).fontSize); // Pobierz aktualny rozmiar czcionki
            let newSize;

            if (action === 'increase') {
                newSize = currentSize + 2; // Zwiększ rozmiar o 2px
            } else if (action === 'decrease') {
                newSize = currentSize - 2; // Zmniejsz rozmiar o 2px
            } else if (action === 'reset') {
                newSize = 16; // Resetuj do domyślnego (16px)
            }

            rootElement.style.fontSize = newSize + 'px'; // Ustaw nowy rozmiar
            localStorage.setItem('fontSize', newSize + 'px'); // Zapisz preferencję w localStorage
        }

        // Ustaw zapisany rozmiar czcionki przy ładowaniu strony
        document.addEventListener('DOMContentLoaded', () => {
            const savedFontSize = localStorage.getItem('fontSize');
            if (savedFontSize) {
                document.documentElement.style.fontSize = savedFontSize;
            }
        });
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Korepetycje') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Additional links can be added here if needed -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item d-flex align-items-center">
                            <button onclick="resizeText('decrease')" class="btn btn-outline-secondary btn-sm mx-1">A-</button>
                            <button onclick="resizeText('reset')" class="btn btn-outline-secondary btn-sm mx-1">A</button>
                            <button onclick="resizeText('increase')" class="btn btn-outline-secondary btn-sm mx-1">A+</button>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <!-- Role-based dashboard links -->
                            @if(Auth::user()->role == 'student')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('student.dashboard') }}">{{ __('Student Dashboard') }}</a>
                                </li>
                            @elseif(Auth::user()->role == 'tutor')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('tutor.dashboard') }}">{{ __('Tutor Dashboard') }}</a>
                                </li>
                            @elseif(Auth::user()->role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('Admin Dashboard') }}</a>
                                </li>
                            @endif

                            <!-- User's name with logout option -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
