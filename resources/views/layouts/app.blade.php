<!DOCTYPE html>
<html lang="fr">
<head>
    @php
        $logoPcData = DB::table('page_blocks')->where('id_block', 47)->first();
        $logoMobileData = DB::table('page_blocks')->where('id_block', 48)->first();
        $logoIcon = DB::table('page_blocks')->where('id_block', 49)->first();
        
        // On définit un fallback (image par défaut) si rien n'est trouvé en BDD
        $pathPc = $logoPcData ? ltrim($logoPcData->image_path, '/') : 'images/logo_armature.png';
        $pathMobile = $logoMobileData ? ltrim($logoMobileData->image_path, '/') : 'images/logo_armature.png';
        $pathIcon = $logoIcon ? ltrim($logoIcon->image_path, '/') : 'images/logo_favicon.png';
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armature Business - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset($pathIcon) }}?v={{ time() }}" >
    <link rel="apple-touch-icon" href="{{ asset($pathIcon) }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        /* STYLE DU PRELOADER PREMIUM */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #020617; /* Ton bleu nuit profond */
            z-index: 10000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.6s ease-in-out, visibility 0.6s;
        }

        .preloader-bundle {
            text-align: center;
            width: 250px; /* Largeur de la ligne */
        }

        .preloader-logo {
            width: 180px;
            height: auto;
            margin-bottom: 25px;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInLogo 0.8s forwards ease-out;
        }

        /* LA LIGNE DE CHARGEMENT */
        .loader-line-container {
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .loader-line-progress {
            width: 0;
            height: 100%;
            background: #3b82f6; /* Ton bleu stratégique */
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            animation: fillLine 2s infinite cubic-bezier(0.65, 0, 0.35, 1);
        }

        @keyframes fadeInLogo {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fillLine {
            0% { width: 0; left: 0; }
            50% { width: 100%; left: 0; }
            100% { width: 100%; left: 100%; }
        }

        .preloader-hidden {
            opacity: 0;
            visibility: hidden;
        }

        
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 bg-white">
    <div id="preloader">
        <div class="preloader-bundle">
            <div class="loader-line-container">
                <div class="loader-line-progress"></div>
            </div>
        </div>
    </div>
  <header class="navbar-premium" style="height: auto; min-height: 80px; padding: 10px 0;">
    <nav class="container d-flex align-items-center justify-content-between">
        
        <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center justify-content-center justify-content-lg-start flex-grow-1 flex-lg-grow-0">
            {{-- LOGO PC : On enlève les contraintes de hauteur fixes de Tailwind --}}
            <img src="{{ asset($pathPc) }}?v={{ time() }}" 
                 alt="Logo Armature" 
                 class="d-none d-lg-block w-auto"
                 style="max-height: 120px; height: auto; display: block;"> 

            {{-- LOGO MOBILE : On le booste aussi --}}
            <img src="{{ asset($pathMobile) }}?v={{ time() }}" 
                 alt="Logo Mobile" 
                 class="d-block d-lg-none w-auto"
                 style="max-height: 70px; height: auto;">
        </a>

        <ul class="nav-links d-none d-lg-flex list-unstyled m-0 align-items-center">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a></li>
            <li><a href="{{ route('home') }}#faq" class="{{ request()->routeIs('about') ? 'active' : '' }}">Qui sommes-nous ?</a></li>
            <li><a href="{{ route('home') }}#testimonials">Témoignages</a></li>
            <li><a href="{{ route('home') }}#faq">FAQ</a></li>
            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Actualités</a></li>
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

    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            // On laisse un petit délai de 800ms pour que l'animation soit vue
            setTimeout(() => {
                preloader.classList.add('preloader-hidden');
            }, 800);
        });
    </script>
</body>
</html>