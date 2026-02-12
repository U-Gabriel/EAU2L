@extends('layouts.admin')

@section('admin_content')
<div class="max-w-[1600px] mx-auto px-4">
    
    @php
        $now = now();
        // On sépare pour gérer l'affichage "Aujourd'hui" même si vide
        $todayFuture = $meetings->filter(function($m) use ($now) {
            return \Carbon\Carbon::parse($m->meeting_date)->isToday() && 
                   \Carbon\Carbon::parse($m->meeting_hour)->isAfter($now);
        });

        $otherFuture = $meetings->filter(function($m) use ($now) {
            return \Carbon\Carbon::parse($m->meeting_date)->isAfter($now) && !\Carbon\Carbon::parse($m->meeting_date)->isToday();
        });
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-12">
        <div class="flex-1">
            <h1 class="text-4xl font-extrabold text-white tracking-tight italic uppercase">Planning & Leads</h1>
            <p class="text-white/40 mt-1 uppercase text-[10px] tracking-[0.2em] font-black">Gestion des consultations clients</p>
            
            <button onclick="openModal('modal-archives')" class="mt-6 bg-white/5 hover:bg-white/10 border border-white/10 text-white/60 hover:text-white px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase transition-all flex items-center gap-2 group">
                <svg class="w-4 h-4 text-blue-500 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"/></svg>
                Voir tout l'historique
            </button>

            {{-- GESTION JOURS NON TRAVAILLÉS --}}
            <div class="mt-8 p-6 bg-white/[0.02] border border-white/5 rounded-[2.5rem] flex flex-wrap items-center gap-6">
                <div class="flex-1">
                    <p class="text-[10px] text-orange-500 font-black uppercase tracking-[0.2em] mb-2">Bloquer une date</p>
                    {{-- ZONE DE NOTIFICATION STYLISÉE --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 animate-pulse">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <p class="text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                                {{ session('success') }}
                            </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center gap-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <p class="text-red-500 text-[10px] font-black uppercase tracking-widest">
                                {{ session('error') }}
                            </p>
                        </div>
                    @endif
                    <form action="{{ route('admin.calendar.store') }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="date" name="date_off" required 
                            class="bg-black/20 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-orange-500 transition-all">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all shadow-lg shadow-orange-500/20">
                            Valider le repos
                        </button>
                    </form>
                </div>

                <div class="h-12 w-px bg-white/5 hidden md:block"></div>

                <div>
                    <p class="text-[10px] text-white/20 font-black uppercase tracking-[0.2em] mb-2 text-center">Voir l'agenda</p>
                    <button onclick="openModal('modal-calendar-view')" class="p-3 bg-white/5 hover:bg-white/10 text-white/40 hover:text-white rounded-2xl transition-all border border-white/5 group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        @if($nextMeeting)
        <div class="w-full md:w-96 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 shadow-2xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/60 text-[10px] font-black uppercase tracking-widest mb-3 italic">Prochain Rendez-vous</p>
                <h3 class="text-2xl font-black text-white leading-none uppercase">{{ $nextMeeting->prenom }} {{ $nextMeeting->nom }}</h3>
                <p class="text-white/80 text-xs font-bold mt-2 flex items-center gap-2">
                    <span class="bg-black/20 px-2 py-1 rounded-lg">{{ \Carbon\Carbon::parse($nextMeeting->meeting_date)->translatedFormat('d F') }}</span>
                    <span>{{ \Carbon\Carbon::parse($nextMeeting->meeting_hour)->format('H:i') }}</span>
                </p>
                <button onclick="openModal('modal-{{ $nextMeeting->id_information_customer }}')" 
                    class="mt-6 w-full bg-white text-blue-600 py-3 rounded-2xl text-[10px] font-black uppercase hover:bg-blue-50 transition-colors shadow-lg font-black">
                    Détails complets
                </button>
            </div>
        </div>
        @endif
    </div>

    {{-- TABLEAU PRINCIPAL --}}
    <div class="bg-[#0f172a]/40 border border-white/5 rounded-[3rem] overflow-hidden backdrop-blur-md shadow-2xl">
        <table class="w-full text-left border-collapse text-white">
            <thead>
                <tr class="bg-white/[0.02] text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">
                    <th class="p-8">Client / Entreprise</th>
                    <th class="p-8">Date & Heure</th>
                    <th class="p-8">Objectif</th>
                    <th class="p-8">Statut</th>
                    <th class="p-8 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                
                {{-- SECTION AUJOURD'HUI --}}
                <tr class="bg-blue-600/10">
                    <td colspan="5" class="px-8 py-4 text-[10px] font-black text-blue-400 uppercase tracking-widest italic border-l-4 border-blue-600">
                        📅 Rendez-vous d'aujourd'hui
                    </td>
                </tr>

                @forelse($todayFuture as $rdv)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-300">
                        <td class="p-8">
                            <div class="flex flex-col">
                                <span class="font-bold text-base">{{ $rdv->prenom }} {{ $rdv->nom }}</span>
                                <span class="text-[10px] text-blue-500 font-black uppercase tracking-widest">{{ $rdv->company_name ?? 'Client Particulier' }}</span>
                            </div>
                        </td>
                        <td class="p-8" data-label="Date & Heure">
                            <div class="flex items-center gap-3">
                                <span class="font-medium italic">{{ \Carbon\Carbon::parse($rdv->meeting_date)->format('d/m/Y') }}</span>
                                <span class="px-2 py-1 bg-white/5 rounded-lg text-white/60 font-mono text-xs">{{ \Carbon\Carbon::parse($rdv->meeting_hour)->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="p-8 text-sm text-white/40 italic font-medium" data-label="Objectif">{{ Str::limit($rdv->rdv_objective, 45) }}</td>
                        <td class="p-8" data-label="Statut">
                            <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-tighter border {{ $rdv->status == 'new' ? 'bg-orange-500/5 text-orange-500 border-orange-500/20' : 'bg-blue-500/5 text-blue-400 border-blue-500/20' }}">● {{ $rdv->status }}</span>
                        </td>
                        <td class="p-8 text-right" data-label="Actions">
                            <button onclick="openModal('modal-{{ $rdv->id_information_customer }}')" class="p-3 bg-white/5 hover:bg-blue-600 hover:text-white rounded-2xl transition-all text-white/20 shadow-lg group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-white/20 text-[10px] font-black uppercase italic tracking-[0.2em] text-center">
                            ✨ Vous n'avez plus de rendez-vous pour aujourd'hui
                        </td>
                    </tr>
                @endforelse

                {{-- SECTION PROCHAINEMENT --}}
                @if($otherFuture->count() > 0)
                <tr class="bg-white/[0.01]">
                    <td colspan="5" class="px-8 py-4 text-[10px] font-black text-white/20 uppercase tracking-widest border-t border-white/5">
                        ⏳ Prochainement
                    </td>
                </tr>
                @foreach($otherFuture as $rdv)
                    <tr class="group hover:bg-white/[0.03] transition-all duration-300">
                        <td class="p-8">
                            <div class="flex flex-col">
                                <span class="font-bold text-base">{{ $rdv->prenom }} {{ $rdv->nom }}</span>
                                <span class="text-[10px] text-blue-500 font-black uppercase tracking-widest">{{ $rdv->company_name ?? 'Client Particulier' }}</span>
                            </div>
                        </td>
                        <td class="p-8 italic text-white/60">{{ \Carbon\Carbon::parse($rdv->meeting_date)->format('d/m/Y') }} à {{ $rdv->meeting_hour }}</td>
                        <td class="p-8 text-sm text-white/40 italic">{{ Str::limit($rdv->rdv_objective, 45) }}</td>
                        <td class="p-8"><span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase border border-white/10">● {{ $rdv->status }}</span></td>
                        <td class="p-8 text-right">
                            <button onclick="openModal('modal-{{ $rdv->id_information_customer }}')" class="p-3 bg-white/5 hover:bg-blue-600 rounded-2xl transition-all text-white/20 hover:text-white shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round"/></svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- MODAUX CLIENTS --}}
@foreach($meetings as $rdv)
<div id="modal-{{ $rdv->id_information_customer }}" class="hidden fixed inset-0 z-[700] flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-xl" onclick="closeModal('modal-{{ $rdv->id_information_customer }}')"></div>
    <div class="bg-[#0f172a] border border-white/10 w-full max-w-5xl rounded-[3rem] relative z-[710] overflow-hidden flex flex-col h-[85vh]">
        
        {{-- Header --}}
        <div class="p-8 bg-white/5 border-b border-white/5 flex justify-between items-start text-white">
            <div>
                <h2 class="text-3xl font-black italic uppercase">{{ $rdv->prenom }} {{ $rdv->nom }}</h2>
                <div class="flex gap-3 mt-2">
                    <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 text-[10px] font-black rounded-md uppercase tracking-widest">{{ $rdv->status }}</span>
                    <span class="text-white/40 text-[10px] font-bold uppercase tracking-[0.2em]">{{ $rdv->company_name }}</span>
                </div>
            </div>
            <button onclick="closeModal('modal-{{ $rdv->id_information_customer }}')" class="text-white/20 hover:text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round"/></svg>
            </button>
        </div>

        {{-- Onglets --}}
        <div class="flex bg-white/[0.02] border-b border-white/5 p-2 gap-2">
            @foreach(['Contact', 'Entreprise', 'Analyse', 'Bilan'] as $index => $tab)
                <button onclick="switchTab('{{ $rdv->id_information_customer }}', {{ $index }})" 
                        id="btn-{{ $rdv->id_information_customer }}-{{ $index }}"
                        class="tab-btn-{{ $rdv->id_information_customer }} flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all {{ $index == 0 ? 'bg-blue-600 text-white' : 'text-white/30 hover:bg-white/5' }}">
                    {{ $tab }}
                </button>
            @endforeach
        </div>

        {{-- Contenu --}}
        <div class="flex-1 overflow-y-auto p-10 custom-scrollbar text-white">
            {{-- Contact --}}
            <div id="tab-{{ $rdv->id_information_customer }}-0" class="tab-content-{{ $rdv->id_information_customer }} space-y-6">
                <div class="grid grid-cols-2 gap-8">
                    <div class="p-8 bg-white/5 rounded-[2.5rem] border border-white/5">
                        <p class="text-[10px] text-blue-500 font-black uppercase mb-2">Email</p>
                        <p class="text-xl font-medium">{{ $rdv->email }}</p>
                    </div>
                    <div class="p-8 bg-white/5 rounded-[2.5rem] border border-white/5">
                        <p class="text-[10px] text-blue-500 font-black uppercase mb-2">Téléphone</p>
                        <p class="text-xl font-medium">{{ $rdv->tel }}</p>
                    </div>
                </div>
            </div>

            {{-- Entreprise (FIX CHAMPS) --}}
            <div id="tab-{{ $rdv->id_information_customer }}-1" class="tab-content-{{ $rdv->id_information_customer }} hidden space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5 col-span-2">
                        <p class="text-[10px] text-blue-400 uppercase font-black mb-1 tracking-widest">Dénomination Sociale</p>
                        <p class="text-2xl font-black italic">{{ $rdv->company_name ?? 'Client Particulier' }}</p>
                    </div>
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                        <p class="text-[10px] text-white/40 uppercase font-black mb-1">Type de société</p>
                        <p class="text-xl font-bold">{{ $rdv->company_type ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                        <p class="text-[10px] text-white/40 uppercase font-black mb-1">Secteur d'activité</p>
                        <p class="text-xl font-bold">{{ $rdv->company_activity ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                        <p class="text-[10px] text-white/40 uppercase font-black mb-1">Nombre Salariés</p>
                        <p class="text-xl font-bold">{{ $rdv->employees_number ?? 'N/A' }}</p>
                    </div>
                    <div class="p-6 bg-emerald-500/10 rounded-3xl border border-emerald-500/20">
                        <p class="text-[10px] text-emerald-500 uppercase font-black mb-1">Marge Théorique</p>
                        <p class="text-2xl font-black italic text-white">{{ $rdv->marge_theorique }}</p>
                    </div>
                </div>
            </div>

            {{-- Analyse & Bilan --}}
            <div id="tab-{{ $rdv->id_information_customer }}-2" class="tab-content-{{ $rdv->id_information_customer }} hidden space-y-8">
                <div><label class="text-[10px] text-blue-500 font-black uppercase tracking-widest italic">🎯 Objectif</label><div class="bg-white/5 p-6 rounded-[2rem] mt-3 border border-white/5 italic text-lg">"{{ $rdv->rdv_objective }}"</div></div>
                <div><label class="text-[10px] text-blue-500 font-black uppercase tracking-widest italic">💡 Attentes</label><div class="bg-white/[0.02] p-6 rounded-[2rem] mt-3 border border-white/5 text-white/60 leading-relaxed">{{ $rdv->user_expectations }}</div></div>
            </div>
            <div id="tab-{{ $rdv->id_information_customer }}-3" class="tab-content-{{ $rdv->id_information_customer }} hidden">
                <div class="bg-black/40 p-10 rounded-[3rem] border border-white/5 font-mono text-sm text-white/70 leading-[2] shadow-2xl">{{ $rdv->bilan }}</div>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- MODAL ARCHIVES --}}
<div id="modal-archives" class="hidden fixed inset-0 z-[600] flex items-center justify-center p-6 text-white">
    <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-2xl" onclick="closeModal('modal-archives')"></div>
    <div class="bg-[#0f172a] border border-white/10 w-full max-w-6xl rounded-[3.5rem] relative z-[610] overflow-hidden flex flex-col h-[85vh] shadow-2xl">
        <div class="p-10 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <div>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter">Historique Complet</h2>
                <p class="text-white/20 text-[10px] font-bold uppercase tracking-[0.3em]">Accès à tous les dossiers passés et futurs</p>
            </div>
            <button onclick="closeModal('modal-archives')" class="text-white/20 hover:text-white font-black text-[10px] border border-white/10 px-6 py-2 rounded-2xl">FERMER</button>
        </div>
        <div class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <table class="w-full text-left">
                <thead class="text-[10px] font-black text-white/20 uppercase tracking-widest border-b border-white/5">
                    <tr>
                        <th class="p-4 italic">Client</th>
                        <th class="p-4 italic">Date</th>
                        <th class="p-4 italic">Heure</th> {{-- Nouvelle colonne --}}
                        <th class="p-4 italic">Statut</th>
                        <th class="p-4 text-right italic">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($meetings as $archived)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="p-4 font-bold">{{ $archived->prenom }} {{ $archived->nom }}</td>
                        <td class="p-4 text-white/40 text-xs italic">{{ \Carbon\Carbon::parse($archived->meeting_date)->format('d/m/Y') }}</td>
                        <td class="p-4 text-blue-500/60 font-mono text-xs">{{ \Carbon\Carbon::parse($archived->meeting_hour)->format('H:i') }}</td> {{-- Heure ajoutée --}}
                        <td class="p-4">
                            <span class="text-[9px] font-black uppercase {{ \Carbon\Carbon::parse($archived->meeting_date.' '.$archived->meeting_hour)->isPast() ? 'text-white/20' : 'text-blue-500' }}">
                                {{ $archived->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            {{-- On cache l'archive mais on garde une trace --}}
                            <button onclick="openClientFromArchive('modal-{{ $archived->id_information_customer }}')" class="text-white/20 group-hover:text-blue-50 text-[10px] font-black uppercase tracking-widest transition-all bg-white/5 px-4 py-2 rounded-xl hover:bg-blue-600">Ouvrir la fiche</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL AGENDA DES JOURS OFF --}}
<div id="modal-calendar-view" class="hidden fixed inset-0 z-[800] flex items-center justify-center p-6 text-white">
    <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-2xl" onclick="closeModal('modal-calendar-view')"></div>
    <div class="bg-[#0f172a] border border-white/10 w-full max-w-2xl rounded-[3rem] relative z-[810] overflow-hidden flex flex-col max-h-[70vh] shadow-2xl">
        <div class="p-8 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <div>
                <h2 class="text-2xl font-black italic uppercase tracking-tighter">Jours Non Travaillés</h2>
                <p class="text-orange-500/60 text-[10px] font-bold uppercase tracking-[0.3em]">Agenda des indisponibilités</p>
            </div>
            <button onclick="closeModal('modal-calendar-view')" class="text-white/20 hover:text-white font-black text-[10px] border border-white/10 px-4 py-2 rounded-xl">FERMER</button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            @if(isset($calendarDays) && $calendarDays->count() > 0)
                <div class="grid grid-cols-1 gap-3">
                    @foreach($calendarDays as $day)
                        <div class="flex justify-between items-center bg-white/5 p-4 rounded-2xl border border-white/5 hover:border-orange-500/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-orange-500/10 rounded-xl flex items-center justify-center text-orange-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-sm uppercase">{{ \Carbon\Carbon::parse($day->date_off)->translatedFormat('l d F Y') }}</p>
                                    <p class="text-[9px] text-white/20 uppercase font-black">Indisponible</p>
                                </div>
                            </div>
                            
                            <form action="{{ route('admin.calendar.destroy', $day->id_calender) }}" method="POST" onsubmit="return confirm('Réactiver ce jour de travail ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-white/10 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-white/20 text-xs font-black uppercase italic italic">Aucun jour de repos planifié</p>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    let wasArchiveOpen = false;

    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';

        // Reset des onglets pour les fiches clients
        if(id.startsWith('modal-') && id !== 'modal-archives') {
            const customerId = id.replace('modal-', '');
            switchTab(customerId, 0); 
        }
    }

    // Fonction spéciale pour ouvrir un client depuis l'archive
    function openClientFromArchive(clientId) {
        wasArchiveOpen = true; // On mémorise qu'on vient de l'archive
        document.getElementById('modal-archives').classList.add('hidden');
        openModal(clientId);
    }

    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        
        // Si on ferme une fiche client ET qu'on venait de l'archive
        if(id.startsWith('modal-') && id !== 'modal-archives' && wasArchiveOpen) {
            wasArchiveOpen = false; // On reset le flag
            document.getElementById('modal-archives').classList.remove('hidden'); // On réouvre l'archive
            return; // On s'arrête là pour ne pas libérer le scroll du body
        }

        // Si plus aucun modal n'est ouvert, on remet le scroll
        if(document.querySelectorAll('[id^="modal-"]:not(.hidden)').length === 0) {
            document.body.style.overflow = 'auto';
            wasArchiveOpen = false;
        }
    }

    function switchTab(rdvId, tabIndex) {
        document.querySelectorAll('.tab-content-' + rdvId).forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn-' + rdvId).forEach(el => {
            el.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
            el.classList.add('text-white/30', 'hover:bg-white/5');
        });

        const content = document.getElementById('tab-' + rdvId + '-' + tabIndex);
        if(content) content.classList.remove('hidden');

        const btn = document.getElementById('btn-' + rdvId + '-' + tabIndex);
        if(btn) {
            btn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
            btn.classList.remove('text-white/30');
        }
    }
</script>
<style>
/* --- FIX OVERLAP MODAL MOBILE --- */
@media (max-width: 768px) {
    /* On force les bulles Email/Tel à se mettre l'une sous l'autre */
    .grid.grid-cols-2 { 
        display: flex !important;
        flex-direction: column !important;
        gap: 15px !important;
    }

    /* On ajuste la taille des bulles pour qu'elles prennent toute la largeur */
    div[class*="bg-white/"] { 
        width: 100% !important;
        min-height: auto !important;
        padding: 20px !important;
    }

    /* On force le texte long (email) à aller à la ligne ou à rétrécir */
    span, p, a {
        word-break: break-all !important; /* Coupe l'email s'il est trop long */
        font-size: 0.9rem !important;
    }
}


</style>
@endsection