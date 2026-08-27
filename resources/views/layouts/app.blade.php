<!DOCTYPE html>
<html lang="fr">
<head>
    @php
        // Récupération dynamique des logos en base de données
        $logoPcData = DB::table('page_blocks')->where('id_block', 47)->first();
        $logoMobileData = DB::table('page_blocks')->where('id_block', 48)->first();
        $logoIcon = DB::table('page_blocks')->where('id_block', 49)->first();
        
        $pathPc = $logoPcData ? ltrim($logoPcData->image_path, '/') : 'images/logo_armature.png';
        $pathMobile = $logoMobileData ? ltrim($logoMobileData->image_path, '/') : 'images/logo_armature.png';
        $pathIcon = ($logoIcon && $logoIcon->image_path) ? ltrim($logoIcon->image_path, '/') : 'images/logo_favicon.png';

        $seoTitle = "Expert en Trésorerie et Rentabilité TPE/PME | Armature Business";
        $seoDesc = "Optimisez votre gestion financière et boostez la rentabilité de votre entreprise avec Armature Business. Accompagnement stratégique sur mesure.";
        $currentUrl = url()->current();
        $vignetteUrl = asset($pathPc);
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO & META TAGS --}}
    <title>Armature Business - @yield('title')</title>
    <meta name="description" content="@yield('meta_description', 'Coach pour entreprise spécialisé en gestion de trésorerie et rentabilité. Armature Business accompagne les dirigeants de TPE/PME.')">
    <meta name="keywords" content="coach, entreprise, consultant, trésorerie, gestion financière, TPE, PME, optimisation rentabilité, Armature Business">
    <link rel="canonical" href="{{ $currentUrl }}">

    @yield('robots')

    {{-- OPEN GRAPH --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:title" content="Armature Business - @yield('title')">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ $vignetteUrl }}">

    {{-- FAVICONS --}}
    <link rel="icon" type="image/png" href="{{ asset($pathIcon) }}">
    <link rel="shortcut icon" href="{{ asset($pathIcon) }}">
    <link rel="apple-touch-icon" href="{{ asset($pathIcon) }}">
    
    {{-- POLICES PRO : Inter (Navigation ultra-pro) & Playfair/Cinzel (Logo prestige) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@1,500;1,600&display=swap" rel="stylesheet">

    {{-- STYLES --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    
    <style>
        :root {
            --bg-header: #0d1527;       /* Bleu nuit profond de la maquette */
            --color-gold: #c6973b;      /* Or de la maquette */
            --color-gold-hover: #dbab4c;
            --text-nav: #ced4da;        /* Gris clair texturé pour les liens inactifs */
            --font-sans: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-sans);
            margin: 0;
            padding: 0;
        }

        /* ============ PRELOADER CLEAN ============ */
        #preloader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: var(--bg-header); 
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease, visibility 0.3s;
        }
        .loader-line { width: 160px; height: 2px; background: rgba(255, 255, 255, 0.1); overflow: hidden; position: relative; }
        .loader-progress { width: 0; height: 100%; background: var(--color-gold); animation: load 1.2s infinite ease-in-out; }
        @keyframes load { 0% { width: 0; left: 0; } 50% { width: 100%; left: 0; } 100% { width: 100%; left: 100%; } }
        .preloader-hidden { opacity: 0; visibility: hidden; }

        /* ============ NAVBAR PREMIUM RESPONSIVE ADAPTATIVE ============ */
        header.navbar-premium {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1050;
            background-color: var(--bg-header) !important;
            height: 75px; /* Épaissit moins la ligne (passé de 90px à 75px) */
            display: flex;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* LOGO ADAPTATIF ET SÉCURISÉ */
        .logo-link {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            flex-shrink: 1;
            min-width: 0;
            max-width: 25%; /* Légèrement resserré pour laisser respirer le menu */
            height: 100%;
        }

        .logo-img {
            max-height: 15px; /* BAISSÉ ICI (au lieu de 54px) : Le logo sera beaucoup plus fin et discret */
            width: auto;
            max-width: 100%;
            object-fit: contain;
            display: block;
        }

        header.navbar-premium .container-fluid {
            max-width: 1440px;
            padding: 0 calc(1rem + 2vw); /* Padding fluide selon la taille d'écran */
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: 100%;
        }

        
        .logo-txt-1 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: clamp(1.1rem, 1.5vw, 1.35rem);
            color: #ffffff;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .logo-txt-2 {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 500;
            font-size: clamp(1.2rem, 1.6vw, 1.45rem);
            color: var(--color-gold);
            margin-left: 5px;
            white-space: nowrap;
        }
        

        /* BLOC DROITE : Menu + Bouton collés ensemble */
        .nav-right-holder {
            display: flex;
            align-items: center;
            gap: clamp(15px, 3vw, 40px); /* Espace adaptatif entre le menu et le bouton */
            flex-shrink: 0; /* Empêche le bloc d'actions d'être écrasé */
        }

        .nav-menu-list {
            display: flex;
            align-items: center;
            gap: clamp(12px, 2vw, 32px); /* Espace adaptatif intelligent entre les liens */
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-menu-list a {
            font-family: var(--font-sans);
            color: var(--text-nav);
            font-size: clamp(0.85rem, 1vw, 0.93rem);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s ease-in-out;
            white-space: nowrap;
            letter-spacing: -0.1px;
        }

        .nav-menu-list a:hover {
            color: #ffffff;
        }

        /* État Actif avec la petite barre sous le lien */
        .nav-menu-list a.active {
            color: #ffffff;
            position: relative;
        }
        .nav-menu-list a.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #3b82f6; 
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* BOUTON PRENDRE RDV */
        .btn-premium-cta {
            background-color: var(--color-gold);
            color: #0d1527 !important;
            font-family: var(--font-sans);
            font-weight: 600;
            font-size: clamp(0.82rem, 0.9vw, 0.92rem);
            padding: 12px clamp(16px, 1.8vw, 24px);
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            white-space: nowrap;
            border: none;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .btn-premium-cta:hover {
            background-color: var(--color-gold-hover);
            transform: translateY(-1px);
        }

        /* RESPONSIVE & BURGER MOBILE */
        .burger-icon {
            background: transparent; border: none; display: flex; flex-direction: column; gap: 5px; cursor: pointer; padding: 5px;
        }
        .burger-icon span { width: 24px; height: 2px; background: #ffffff; transition: 0.3s; }

        .sidebar-mobile {
            position: fixed; top: 0; right: -100%; width: 280px; height: 100vh;
            background: var(--bg-header); z-index: 2000; padding: 100px 30px;
            display: flex; flex-direction: column; gap: 24px;
            box-shadow: -5px 0 25px rgba(0,0,0,0.3); transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-mobile.active { right: 0; }
        .sidebar-mobile a { color: var(--text-nav); text-decoration: none; font-size: 1.1rem; font-weight: 500; }
        .sidebar-mobile a.active { color: #ffffff; }
        .sidebar-mobile .btn-premium-cta { text-align: center; margin-top: 20px; }

       

        /* POINT DE RUPTURE NETTOYÉ : Bascule sur mobile dès que l'espace manque */
        @media (max-width: 1140px) {
            .nav-right-holder { display: none !important; }
            .burger-icon { display: flex !important; }
        }
        
        @media (max-width: 1024px) {
            header.navbar-premium .container-fluid {
        position: relative;
        justify-content: center; /* Centre le contenu principal sur mobile */
    }

    .logo-link {
        max-width: 60%; /* Donne plus d'espace au logo pour s'exprimer */
        justify-content: center;
        margin: 0 auto;
    }

    .logo-img {
        max-height: 38px !important; /* On l'agrandit nettement pour qu'il soit bien visible */
    }

    /* On force le bouton burger à rester absolument à droite */
    .burger-icon {
        position: absolute;
        right: calc(1rem + 2vw);
        top: 50%;
        transform: translateY(-50%);
    }
            
            
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>

<body>

    {{-- PRELOADER --}}
    <div id="preloader">
        <div class="loader-line">
            <div class="loader-progress"></div>
        </div>
    </div>

    {{-- HEADER STRUCTURE PRO ADAPTATIVE --}}
    <header class="navbar-premium">
        <div class="container-fluid">
            
            {{-- LOGO GAUCHE --}}
            <a href="{{ route('home') }}" class="logo-link">
                @if($logoPcData || $logoMobileData)
                    <img src="{{ asset($pathPc) }}?v={{ time() }}" alt="Armature Business" class="logo-img">
                @else
                    <div class="logo-txt-1">ARMATURE<span class="logo-txt-2">Business</span></div>
                @endif
            </a>

            {{-- BLOC DROITE REGROUPÉ --}}
            <div class="nav-right-holder">
                <ul class="nav-menu-list">
                    <li>
                        <a href="{{ route('home') }}#problemes" class="{{ request()->routeIs('home') && !request()->css ? 'active' : '' }}">Vos enjeux</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#strategy">Notre méthode</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#tarifs">Nos tarifs</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#testimonials">Témoignages</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#about">Qui sommes-nous ?</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#faq">FAQ</a>
                    </li>
                    <li>
                        <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Actualités</a>
                    </li>
                </ul>
                
                {{-- LIEN BOUTON RDV --}}
                @if(Route::has('contact'))
                    <a href="{{ route('contact') }}" class="btn-premium-cta">Prendre RDV</a>
                @else
                    <a href="{{ route('home') }}#contact" class="btn-premium-cta">Prendre RDV</a>
                @endif
            </div>

            {{-- BURGER MOBILE --}}
            <button class="burger-icon d-block d-lg-none" id="mobileMenuBtn" aria-label="Ouvrir le menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header> 

    {{-- TIROIR MOBILE --}}
    <div class="sidebar-mobile d-lg-none" id="mobileDrawer">
        <a href="{{ route('home') }}#problemes">Vos enjeux</a>
        <a href="{{ route('home') }}#strategy">Notre méthode</a>
        <a href="{{ route('home') }}#tarifs">Nos tarifs</a>
        <a href="{{ route('home') }}#testimonials">Témoignages</a>
        <a href="{{ route('home') }}#about">Qui sommes-nous ?</a>
        <a href="{{ route('home') }}#faq">FAQ</a>
        <a href="{{ route('blog.index') }}">Actualités</a>
        @if(Route::has('contact'))
            <a href="{{ route('contact') }}" class="btn-premium-cta">Prendre RDV</a>
        @else
            <a href="{{ route('home') }}#contact" class="btn-premium-cta">Prendre RDV</a>
        @endif
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('layouts.footer')

    
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('mobileMenuBtn');
            const drawer = document.getElementById('mobileDrawer');
            
            if (menuBtn && drawer) {
                menuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    drawer.classList.toggle('active');
                });

                document.addEventListener('click', function(e) {
                    if (!drawer.contains(e.target) && !menuBtn.contains(e.target)) {
                        drawer.classList.remove('active');
                    }
                });

                drawer.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        drawer.classList.remove('active');
                    });
                });
            }
        });

        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.add('preloader-hidden');
            }
        });
    </script>

    {{-- Bootstrap Bundle DOIT être une balise séparée, pas dans un autre script --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>