@extends('layouts.admin')

@section('admin_content')
<div class="max-w-[1500px] mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight italic">Traffic Monitor</h1>
            <p class="text-white/40 mt-1 uppercase text-xs tracking-[0.2em] font-bold">Analyse des flux en temps réel</p>
        </div>
        
        <form action="{{ route('admin.stats') }}" method="GET" class="flex flex-wrap gap-4 p-6 bg-white/[0.02] border border-white/10 rounded-[2rem] mb-8">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black text-blue-500 uppercase ml-2">Par Date</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                    class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500 outline-none transition-all">
            </div>

            <div class="flex flex-col gap-1 flex-1">
                <label class="text-[10px] font-black text-blue-500 uppercase ml-2">Rechercher une Page</label>
                <input type="text" name="page" placeholder="ex: /admin/users..." value="{{ request('page') }}"
                    class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500 outline-none transition-all w-full">
            </div>

            <div class="flex items-end pb-1">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-2.5 rounded-xl text-xs font-bold transition-all shadow-lg shadow-blue-600/20">
                    APPLIQUER LES FILTRES
                </button>
                <a href="{{ route('admin.stats') }}" class="bg-white/5 hover:bg-white/10 text-white/50 hover:text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase transition-all flex items-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white/[0.02] border border-white/10 rounded-[2rem] p-8 backdrop-blur-md">
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Vues Totales</p>
            <p class="text-4xl font-black text-white">{{ number_format($totalViews) }}</p>
            <div class="w-full bg-blue-500/20 h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-blue-500 h-full w-2/3"></div>
            </div>
        </div>
        <div class="bg-white/[0.02] border border-white/10 rounded-[2rem] p-8 backdrop-blur-md">
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Visiteurs Uniques</p>
            <p class="text-4xl font-black text-emerald-400">{{ number_format($uniqueVisitors) }}</p>
            <div class="w-full bg-emerald-500/20 h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full w-1/2"></div>
            </div>
        </div>
        <div class="bg-white/[0.02] border border-white/10 rounded-[2rem] p-8 backdrop-blur-md">
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Top Destination</p>
            <p class="text-sm font-bold text-white truncate" title="{{ $topPage->concerning_page }}">
                {{ $topPage->concerning_page }}
            </p>
            <p class="text-blue-500 text-[10px] font-black mt-2 uppercase">
                {{ $topPage->views ?? 0 }} VISITES
            </p>
        </div>
    </div>

   <div class="bg-white/[0.02] border border-white/10 rounded-[2.5rem] p-8 mb-12">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] flex items-center gap-3">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Pages les plus consultées
            </h3>
            
            {{-- Bouton pour réduire/agrandir --}}
            <button onclick="toggleTopPages()" id="btn-toggle-pages" class="text-[10px] font-black text-white/20 hover:text-blue-400 uppercase tracking-widest transition-colors flex items-center gap-2">
                <span id="toggle-text">Réduire la liste</span>
                <svg id="toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        <div id="top-pages-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-500 overflow-hidden">
            @foreach($pagesStats as $stat)
            @php
                $cleanPath = str_replace(['http://37.187.183.97', 'https://37.187.183.97'], '', $stat->concerning_page);
                $displayName = $cleanPath == '' || $cleanPath == '/' ? 'Accueil' : ucfirst(ltrim($cleanPath, '/'));
            @endphp

            <div class="group flex items-center justify-between p-4 bg-white/[0.03] hover:bg-blue-500/5 rounded-2xl border border-white/5 hover:border-blue-500/20 transition-all duration-300">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center shrink-0 border border-white/5 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-blue-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs font-bold text-white truncate">{{ $displayName }}</span>
                        <span class="text-[9px] font-mono text-white/20 truncate italic">{{ $cleanPath }}</span>
                    </div>
                </div>
                <div class="shrink-0 bg-blue-500/10 px-3 py-1 rounded-lg border border-blue-500/10">
                    <span class="text-xs font-black text-blue-400">{{ $stat->views }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Titre de la section du bas --}}
    <div class="flex items-center gap-4 mb-6 px-4">
        <h3 class="text-xs font-black text-white/40 uppercase tracking-[0.3em]">Dernières visites en temps réel</h3>
        <div class="h-[1px] flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
    </div>

        
    </div>

    <div class="bg-white/[0.01] border border-white/5 rounded-[2.5rem] overflow-x-auto backdrop-blur-sm">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="bg-white/5 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">
                    <th class="p-6 w-[15%]">Horodatage</th>
                    <th class="p-6 w-[15%]">IP / Session</th>
                    <th class="p-6 w-[35%]">Page Cible</th>
                    <th class="p-6 w-[25%]">Provenance</th>
                    <th class="p-6 w-[10%] text-right">Device</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-sm">
                @foreach($movements as $m)
                <tr class="group hover:bg-white/[0.03] transition-all duration-300">
                    <td class="p-6">
                        <div class="flex items-center gap-2">
                            @if(\Carbon\Carbon::parse($m->created_at)->isAfter(now()->subMinutes(5)))
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            @endif
                            <span class="text-white font-medium italic opacity-80">
                                {{ \Carbon\Carbon::parse($m->created_at)->locale('fr')->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-[9px] text-white/20 mt-1">{{ $m->created_at }}</p>
                    </td>

                    <td class="p-6">
                        <div class="flex flex-col">
                            <span class="text-blue-400 font-bold tracking-tight text-xs">{{ $m->visitor_ip }}</span>
                            <span class="text-[9px] text-white/20 font-mono truncate w-24" title="{{ $m->session_id }}">{{ $m->session_id }}</span>
                        </div>
                    </td>

                    <td class="p-6">
                        <div class="max-w-[400px]"> {{-- Limite la largeur de l'URL --}}
                            <code onclick="copyToClipboard('{{ $m->concerning_page }}')" 
                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-[13px] text-blue-300 font-mono cursor-pointer group-hover:border-blue-500/30 hover:bg-blue-500/20 transition-all break-all line-clamp-2 inline-block leading-relaxed"
                                title="Cliquez pour copier l'URL complète">
                                {{ $m->concerning_page }}
                            </code>
                        </div>
                    </td>

                    <td class="p-6 text-sm text-white/30">
                        <div class="max-w-[200px] truncate text-[11px]">
                            @if($m->referrer_url)
                                <span onclick="copyToClipboard('{{ $m->referrer_url }}')" 
                                    class="cursor-pointer hover:text-white transition-all" 
                                    title="{{ $m->referrer_url }}">
                                    {{ $m->referrer_url }}
                                </span>
                            @else
                                <span class="text-[10px] uppercase font-black opacity-20 italic text-white/10">Direct</span>
                            @endif
                        </div>
                    </td>

                    <td class="p-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            @if(Str::contains(strtolower($m->device_type), 'mobile'))
                                <svg class="w-4 h-4 text-orange-400 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="w-4 h-4 text-blue-400 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                            <span class="text-[9px] font-black uppercase text-white/40">{{ $m->device_type ?? '???' }}</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $movements->links() }}
    </div>
</div>
<div id="copy-toast">URL COPIÉE !</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.getElementById('copy-toast');
        toast.style.transform = 'translateY(0)';
        setTimeout(() => {
            toast.style.transform = 'translateY(100px)';
        }, 2000);
    });
}

function toggleTopPages() {
    const container = document.getElementById('top-pages-container');
    const text = document.getElementById('toggle-text');
    const icon = document.getElementById('toggle-icon');

    if (container.style.display === "none") {
        container.style.display = "grid";
        text.innerText = "Réduire la liste";
        icon.style.transform = "rotate(0deg)";
    } else {
        container.style.display = "none";
        text.innerText = "Afficher la liste";
        icon.style.transform = "rotate(-90deg)";
    }
}
</script>
@endsection