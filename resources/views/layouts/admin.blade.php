<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAU2L - @yield('title', 'Administration')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo4.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo4.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Style pour le lien actif dans le header */
        .nav-link-active {
            background: rgba(37, 99, 235, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="bg-[#020617] text-slate-200 antialiased min-h-screen">

    <header class="sticky top-0 z-[100] w-full border-b border-white/5 bg-[#020617]/80 backdrop-blur-xl">
        <div class="max-w-[1600px] mx-auto px-6 h-20 flex items-center justify-between">
            
            <div class="flex items-center gap-8">
                

                <nav class="relative z-[100] px-6 py-8">
                    <div class="flex items-center justify-between gap-4">
                        <a href="/hlqzfhjzm546FG65ERF/admin/dashboard" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="leading-none">
                                <span class="text-white font-extrabold text-xl tracking-tighter block uppercase">Admin</span>
                                <span class="text-[10px] text-blue-500 font-black uppercase tracking-[0.2em]">EAU2L Panel</span>
                            </div>
                        </a>
                        
                        {{-- Emplacement de ton logo existant à gauche --}}
                        <div class="shrink-0">
                            {{-- Ton logo actuel est ici --}}
                        </div>

                        {{-- Navigation PC : Style "Capsule" Minimaliste --}}
                        <div class="hidden md:flex items-center bg-white/[0.03] backdrop-blur-md border border-white/10 p-1.5 rounded-2xl">
                            @php
                                $navItems = [
                                    'admin.users.index' => 'Utilisateurs',
                                    'admin.planning.index' => 'Planning',
                                    'admin.modifications' => 'Pages',
                                    'admin.stats' => 'Stats'
                                ];
                            @endphp

                            @foreach($navItems as $route => $label)
                                <a href="{{ route($route) }}" 
                                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 {{ request()->routeIs($route) ? 'bg-blue-600 text-white shadow-[0_0_20px_rgba(37,99,235,0.3)]' : 'text-white/30 hover:text-white hover:bg-white/5' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>

                        {{-- Actions Droite (Profil / Déconnexion) --}}
                        <div class="flex items-center gap-3">
                            <button onclick="toggleMobileMenu()" class="md:hidden p-3 bg-white/5 rounded-2xl border border-white/10 text-white active:scale-90 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 8h16M4 16h16" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Dropdown Mobile : Design Pro --}}
                    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-6 right-6 z-[110] mt-4 p-3 bg-[#0a0a0c]/90 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] shadow-2xl">
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($navItems as $route => $label)
                                <a href="{{ route($route) }}" 
                                class="flex items-center justify-between p-5 rounded-[1.8rem] {{ request()->routeIs($route) ? 'bg-blue-600 text-white' : 'bg-white/[0.03] text-white/50' }} transition-all">
                                    <span class="text-[11px] font-black uppercase tracking-widest">{{ $label }}</span>
                                    @if(request()->routeIs($route))
                                        <div class="w-2 h-2 rounded-full bg-white animate-pulse"></div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>
            </div>

            <div class="flex items-center gap-6">
                <a href="/" target="_blank" class="hidden sm:flex items-center gap-2 text-xs font-bold text-white/30 hover:text-white transition-all uppercase tracking-widest">
                    <span>Live Site</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <div class="flex items-center gap-4 pl-6 border-l border-white/10">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->pseudo }}</p>
                        <p class="text-[9px] text-green-500 font-black uppercase tracking-tighter mt-1 flex items-center justify-end gap-1">
                            <span class="w-1 h-1 bg-green-500 rounded-full animate-pulse"></span>
                            Connecté
                        </p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="relative group">
                        @csrf
                        <button type="submit" 
                            class="w-11 h-11 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 transition-all duration-300 group-hover:bg-red-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(239,68,68,0.3)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            
                            <span class="absolute -bottom-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-red-500 text-white text-[10px] font-bold rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none uppercase tracking-tighter">
                                Déconnexion
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto px-6 py-10">
        @yield('admin_content')
    </main>

    <footer class="max-w-[1600px] mx-auto px-6 py-10 border-t border-white/5 text-center text-white/10 text-[10px] font-bold uppercase tracking-[0.5em]">
        &copy; 2026 Espace Privé - Ne pas partager les URLs
    </footer>
<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        // Ajout d'une petite transition simple
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            menu.style.opacity = '0';
            setTimeout(() => menu.style.opacity = '1', 10);
        } else {
            menu.classList.add('hidden');
        }
    }


    // Fermer le menu si on clique en dehors
    window.onclick = function(event) {
        const menu = document.getElementById('mobile-menu');
        if (!event.target.closest('nav') && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
        }
    }
</script>
</body>
</html>