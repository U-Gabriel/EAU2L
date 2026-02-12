@extends('layouts.admin')

@section('title', 'Tableau de Bord')


@section('admin_content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight italic">Tableau de Bord</h1>
            <p class="text-white/40 mt-1 uppercase text-[10px] font-black tracking-[0.2em]">Analyse des performances en temps réel</p>
        </div>
        <div class="flex items-center gap-3 bg-white/5 border border-white/10 p-2 rounded-2xl">
            <span class="flex h-3 w-3 relative ml-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="text-[10px] font-black uppercase tracking-widest pr-2 text-white/70">Système Actif</span>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        {{-- Vues Totales --}}
        <div class="p-8 rounded-[2.5rem] bg-white/[0.02] border border-white/5 hover:border-blue-500/30 transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Vues Totales</p>
            <h3 class="text-4xl font-black text-white tracking-tighter">{{ number_format($totalViews ?? 0) }}</h3>
        </div>

        {{-- Visiteurs Uniques --}}
        <div class="p-8 rounded-[2.5rem] bg-white/[0.02] border border-white/5 hover:border-emerald-500/30 transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 flex items-center justify-center text-emerald-500 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2"/></svg>
            </div>
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Visiteurs Uniques</p>
            <h3 class="text-4xl font-black text-emerald-400 tracking-tighter">{{ number_format($uniqueVisitors ?? 0) }}</h3>
        </div>

        {{-- RDV à venir (Dynamique si tu as la variable) --}}
        <div class="p-8 rounded-[2.5rem] bg-white/[0.02] border border-white/5 hover:border-indigo-500/30 transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-500 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Indisponibilités</p>
            <h3 class="text-4xl font-black text-white tracking-tighter">{{ $calendarDays->count() ?? 0 }}</h3>
        </div>

        {{-- Top Destination --}}
        <div class="p-8 rounded-[2.5rem] bg-white/[0.02] border border-white/5 hover:border-amber-500/30 transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-amber-600/10 flex items-center justify-center text-amber-500 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Top Page</p>
            <h3 class="text-sm font-bold text-white truncate" title="{{ $topPage->concerning_page ?? '/' }}">
                {{ str_replace(['http://37.187.183.97', 'https://'], '', $topPage->concerning_page ?? '/') }}
            </h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- FLUX DE NAVIGATION RÉEL --}}
        <div class="lg:col-span-2 bg-white/[0.01] border border-white/5 rounded-[2.5rem] p-8 backdrop-blur-md">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-bold text-white italic">Dernières activités</h4>
                <a href="{{ route('admin.stats') }}" class="text-[10px] font-black uppercase tracking-widest text-blue-500 hover:text-white transition-colors">Voir les stats complètes</a>
            </div>
            
            <div class="space-y-4">
                @forelse($movements->take(5) as $m)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ str_replace(['http://37.187.183.97', 'https://'], '', $m->concerning_page) }}</p>
                            <p class="text-[10px] text-white/30 uppercase font-black tracking-tighter">
                                {{ \Carbon\Carbon::parse($m->created_at)->diffForHumans() }} • IP: {{ $m->visitor_ip }}
                            </p>
                        </div>
                    </div>
                    <span class="text-[9px] px-2 py-1 bg-white/5 rounded-lg border border-white/10 text-white/40 uppercase font-black shrink-0">
                        {{ $m->device_type ?? 'PC' }}
                    </span>
                </div>
                @empty
                <div class="py-10 text-center text-white/20 italic text-sm">
                    Aucun mouvement détecté pour le moment.
                </div>
                @endforelse
            </div>
        </div>

        {{-- COLONNE DROITE : ACTIONS & PROCHAIN RDV --}}
        <div class="flex flex-col gap-6">
            {{-- BLOC PROCHAIN RDV DYNAMIQUE --}}
            <div class="bg-gradient-to-b from-blue-600/10 to-transparent border border-blue-500/20 rounded-[2.5rem] p-8">
                <h4 class="text-xl font-bold text-white mb-8 italic">Agenda</h4>
                
                @if($nextMeeting)
                    <div class="p-6 rounded-3xl bg-blue-600 text-white shadow-xl shadow-blue-500/20 mb-6 group">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-2">
                            {{ \Carbon\Carbon::parse($nextMeeting->meeting_date)->locale('fr')->isoFormat('D MMMM') }} à {{ substr($nextMeeting->meeting_hour, 0, 5) }}
                        </p>
                        
                        {{-- On affiche le nom/prénom ou "Client inconnu" si les champs sont vides --}}
                        <h5 class="text-xl font-bold mb-1 truncate">
                            {{ $nextMeeting->prenom ?? '' }} {{ $nextMeeting->nom ?? $nextMeeting->nom ?? 'Client' }}
                        </h5>
                        
                        <p class="text-sm opacity-80 mb-6 font-medium line-clamp-1">
                            {{ $nextMeeting->reason ?? 'Audit standard' }}
                        </p>
                        
                        <a href="{{ route('admin.planning.index') }}" class="block w-full py-3 bg-white text-blue-600 rounded-xl font-black text-center text-[10px] uppercase tracking-widest hover:scale-105 transition-transform">
                            DÉTAILS & PLANNING
                        </a>
                    </div>
                @else
                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/5 text-white/40 mb-6 text-center italic">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/>
                        </svg>
                        <p class="text-xs font-bold uppercase tracking-widest">Aucun rendez-vous</p>
                        <p class="text-[10px] mt-1 opacity-50">Prévu prochainement</p>
                    </div>
                @endif

                {{-- ACTIONS RAPIDES --}}
                <div class="space-y-3">
                    <a href="{{ route('admin.planning.index') }}" class="w-full p-4 rounded-2xl border border-white/10 bg-white/5 text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center justify-between group">
                        Gérer mes disponibilités
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="w-full p-4 rounded-2xl border border-white/10 bg-white/5 text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center justify-between group">
                        Liste des Utilisateurs
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection