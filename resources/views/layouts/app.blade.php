<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAU2L - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo4.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo4.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 bg-white">
   <header class="navbar-premium">
    <nav class="container d-flex align-items-center justify-content-between py-2">
        <a href="{{ route('home') }}" class="navbar-brand w-100 w-lg-auto text-center text-lg-start m-0">
            <img src="{{ asset('images/logo3.png') }}" alt="Logo" class="h-12 w-auto mx-auto mx-lg-0">
        </a>

        <ul class="nav-links d-none d-lg-flex list-unstyled m-0 align-items-center">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a></li>
            <li><a href="{{ route('home') }}#faq" class="{{ request()->routeIs('about') ? 'active' : '' }}">Qui sommes-nous ?</a></li>
            <li><a href="{{ route('home') }}#testimonials">Témoignages</a></li>
            <li><a href="{{ route('home') }}#faq">FAQ</a></li>
        </ul>

        <span class="nav-separator d-none d-lg-block"></span>

        <div class="d-none d-lg-block">
            <a href="{{ route('contact') }}" class="btn-nav">
                Prendre un rendez-vous
            </a>
        </div>
    </nav>
</header>
    <main >
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>AOS.init();</script>

    @stack('scripts')
</body>
</html>