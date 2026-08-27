@extends('layouts.admin')

@section('admin_content')
<div class="p-4 md:p-8">

    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        {{-- LE BOUTON BLOG --}}
        <a href="{{ route('admin.blog.index') }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl transition-all hover:scale-105 active:scale-95 shadow-xl shadow-blue-500/20">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 2v4h4" />
            </svg>
            <span class="text-white font-black uppercase tracking-widest text-xs">Gérer le Blog</span>
        </a>
    </div>
    
    <div class="mb-10">
        <h1 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter">Édition <span class="text-blue-500">Contenu</span></h1>
        <p class="text-white/40 text-sm mt-2">Interface simplifiée pour la gestion de la page d'accueil.</p>
    </div>

    {{-- MODAL DE SUCCÈS RESPONSIVE --}}
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 z-[99] flex items-end md:items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-[#1a1a1c] border border-white/10 w-full max-w-sm rounded-[2rem] p-6 text-center shadow-2xl transform transition-all scale-100">
            <div class="w-16 h-16 bg-emerald-500/20 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-white font-black uppercase tracking-widest text-lg">Bravo !</h3>
            <p class="text-white/60 text-sm mt-2">{{ session('success') }}</p>
            <button onclick="document.getElementById('successModal').remove()" class="mt-6 w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold uppercase tracking-widest text-xs transition-colors">
                Génial, merci
            </button>
        </div>
    </div>
    <script>setTimeout(() => { document.getElementById('successModal')?.remove(); }, 3000);</script>
    @endif

    <div class="space-y-10">
        

        {{-- SECTION ÉDITION DU MAIL DE CONFIRMATION --}}
        @php
            $mailBlock = DB::table('page_blocks')->where('type', 'email_audit_confirmation')->first();
        @endphp

        @if($mailBlock)
        <section class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden">
            <div class="bg-white/5 px-6 py-4 border-b border-white/5 flex justify-between items-center">
                <h2 class="text-emerald-500 font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Contenu du Mail de Confirmation
                </h2>
                <span class="text-[9px] text-white/20 font-mono italic text-right">ID Bloc: #{{ $mailBlock->id_block }}</span>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <h3 class="text-white font-bold text-sm">Instructions</h3>
                    <p class="text-white/40 text-[11px] leading-relaxed italic">
                        Rédigez ici votre message. Le header bleu et le bouton Teams sont automatiques.
                    </p>
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-4">
                        <p class="text-[9px] font-bold text-blue-400 uppercase mb-2">Variables magiques</p>
                        <div class="grid grid-cols-2 gap-1 text-[10px] font-mono">
                            <span class="text-white/40">{prenom}</span>
                            <span class="text-white/40">{societe}</span>
                            <span class="text-white/40">{date}</span>
                            <span class="text-white/40">{heure}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <form action="{{ route('admin.block.updateMail', $mailBlock->id_block) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <textarea id="mail_content_db" name="content" class="ignore-quill-global hidden">{!! $mailBlock->content !!}</textarea>
                            
                            <div id="quill_mail_editor" style="height: 300px;" class="bg-white/5 rounded-xl border border-white/10 text-white">
                                {!! $mailBlock->content !!}
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] transition-all active:scale-95 shadow-lg shadow-emerald-900/20">
                                Mettre à jour le modèle d'email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        @endif
        {{-- SECTION LIEN TEAMS POUR RDV CLIENTS --}}
        @php
            $currentTeamsUrl = $teamsBlock->link ?? '';
        @endphp

        <section class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mt-10">
            <div class="bg-white/5 px-6 py-4 border-b border-white/5 flex justify-between items-center">
                <h2 class="text-blue-500 font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    Lien Microsoft Teams pour les Rendez-vous
                </h2>
                @if(isset($teamsBlock) && $teamsBlock)
                    <span class="text-[9px] text-white/20 font-mono italic text-right">ID Bloc: #{{ $teamsBlock->id_block }}</span>
                @endif
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="space-y-2">
                    <h3 class="text-white font-bold text-sm">Instructions Lien Teams</h3>
                    <p class="text-white/40 text-[11px] leading-relaxed italic">
                        Ce lien sera automatiquement inséré dans les e-mails de confirmation de rendez-vous envoyés aux utilisateurs pour rejoindre leur visio-conférence Teams.
                    </p>
                </div>

                <div class="lg:col-span-2">
                    <form action="{{ route('admin.block.updateTeamsLink') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">
                                URL de la réunion Teams
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="url" name="teams_url" required 
                                    value="{{ old('teams_url', $currentTeamsUrl) }}" 
                                    placeholder="https://teams.microsoft.com/l/meetup-join/..." 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white text-sm focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] transition-all active:scale-95 shadow-lg shadow-blue-900/20">
                            Mettre à jour le lien Teams
                        </button>
                    </form>
                </div>
            </div>
        </section>

        
        <div class="space-y-8 bg-[#0d0d0f] border border-white/5 p-6 md:p-8 rounded-[2.5rem] mt-8">
            <div class="border-b border-white/5 pb-4">
                <h2 class="text-blue-500 font-black uppercase tracking-widest text-xs">Administration des E-mails Administrateurs</h2>
                <p class="text-white/60 text-xs mt-1">Gérez les adresses e-mail qui reçoivent les notifications de réservation (laissez vide pour désactiver).</p>
            </div>

            @php
                $adminMailBlock = \App\Models\PageBlock::where('type', 'admin_mail')->first();
            @endphp

            @if($adminMailBlock)
                @php 
                    $data = json_decode($adminMailBlock->content, true);
                    if (!is_array($data)) {
                        $data = $adminMailBlock->content ? [trim($adminMailBlock->content)] : [];
                    }
                @endphp

                <form action="{{ route('admin.block.update', $adminMailBlock->id_block) }}" method="POST" class="bg-black/30 border border-white/5 p-6 rounded-2xl space-y-4">
                    @csrf
                    
                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                        <span class="text-xs font-bold text-white uppercase tracking-wider">
                            Destinataires des notifications 
                            <span class="text-white/30 font-mono ml-2">#{{ $adminMailBlock->id_block }}</span>
                        </span>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Enregistrer
                        </button>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">
                            Adresses e-mail des administrateurs
                        </label>

                        {{-- Liste dynamique des e-mails --}}
                        <div class="space-y-2 p-4 bg-black/20 rounded-xl border border-white/5" id="list-container-admin-emails">
                            @forelse($data as $index => $email)
                                <div class="flex items-center gap-2">
                                    <input type="email" name="content_json[]" value="{{ html_entity_decode(strip_tags($email), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}" placeholder="admin@example.com" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-blue-500">
                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300 px-2 py-1 text-xs font-bold">×</button>
                                </div>
                            @empty
                                <p id="no-admin-text" class="text-white/30 text-xs text-center py-2">Aucun administrateur configuré. Aucun mail ne sera envoyé.</p>
                            @endforelse
                        </div>
                        
                        <button type="button" onclick="addAdminEmailRow()" class="mt-2 text-[10px] text-blue-400 hover:text-blue-300 font-bold uppercase tracking-wider block">
                            + Ajouter un e-mail
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 bg-black/20 border border-white/5 rounded-xl text-center">
                    <p class="text-white/40 text-xs">Aucun bloc 'admin_mail' trouvé en base de données.</p>
                </div>
            @endif
        </div>

        {{-- Script JS --}}
        <script>
        function addAdminEmailRow() {
            const container = document.getElementById('list-container-admin-emails');
            if (!container) return;
            
            const emptyText = document.getElementById('no-admin-text');
            if (emptyText) emptyText.remove();

            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="email" name="content_json[]" value="" placeholder="nouveau-admin@example.com" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-blue-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300 px-2 py-1 text-xs font-bold">×</button>
            `;
            container.appendChild(div);
        }
        </script>
   
        {{-- BOUCLE PRINCIPALE DES SECTIONS --}}
        @foreach($blocks as $type => $group)
            @php 
                $ignoredSections = ['carousel', 'hero', 'text', 'text_image', 'container_marketing', 'featurette', 'featurette-divider', 'situations', 'goals', 'video_presentation', 'meet_goals', 'method', 'method_title','tarif_title', 'tarif_title_card_01', 'tarif_card_01', 'tarif_card_02', 'us_company', 'details_company', 'card_company', 'admin_mail'];
                if(in_array(strtolower($type), $ignoredSections)) continue;

                $isBeforeHome = (strtolower($type) == 'before_home');
            @endphp

            <section class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mb-10">
                <div class="bg-white/5 px-6 py-4 border-b border-white/5 flex justify-between items-center">
                    <h2 class="text-blue-500 font-black uppercase tracking-widest text-[10px] md:text-xs">
                        {{ $isBeforeHome ? 'En-tête de la Page Début' : strtoupper(str_replace('_', ' ', $type)) }}
                    </h2>
                </div>

                <div class="p-6 md:p-8 space-y-12">
                    @foreach($group as $block)
                        @php 
                            $data = json_decode($block->content, true); 
                            $isFAQ = (strtolower($type) == 'faq');
                        @endphp
                        
                        <form action="{{ route('admin.block.update', $block->id_block) }}" method="POST" enctype="multipart/form-data" 
                            class="block pb-10 border-b border-white/5 last:border-0 last:pb-0 transition-all rounded-3xl {{ session('last_updated') == $block->id_block ? 'ring-1 ring-blue-500/30 bg-blue-500/5 p-4' : '' }}">
                            @csrf
                            
                            {{-- Conteneur en grille pour les inputs --}}
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                                <div class="space-y-5 lg:col-span-2">
                                    @if(is_array($data))
                                        @foreach($data as $key => $value)
                                            @if(in_array($key, ['button_secondary_text', 'button_secondary_link', 'video_link'])) @continue @endif

                                            <div>
                                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">
                                                    @if($isBeforeHome && $key === 'eyebrow') Surtitre (Petits caractères du haut)
                                                    @elseif($isBeforeHome && $key === 'big_text') Titre Principal (Grand texte)
                                                    @elseif($isBeforeHome && $key === 'small_text') Paragraphe de Description (Texte d'accompagnement)
                                                    @elseif($isBeforeHome && $key === 'button_primary_text') Texte du bouton principal
                                                    @elseif($isBeforeHome && $key === 'button_primary_link') Lien du bouton principal
                                                    @else {{ str_replace('_', ' ', $key) }} @endif
                                                </label>

                                                @if($isBeforeHome && in_array($key, ['big_text', 'small_text']))
                                                    <div class="space-y-2">
                                                        <textarea id="hidden_{{ $block->id_block }}_{{ $key }}" name="content_json[{{ $key }}]" class="hidden">{!! is_array($value) ? json_encode($value) : $value !!}</textarea>
                                                        <div class="quill-dynamic-editor bg-white/5 rounded-xl border border-white/10 text-white" data-target="hidden_{{ $block->id_block }}_{{ $key }}" style="height: 120px;">
                                                            {!! is_array($value) ? json_encode($value) : $value !!}
                                                        </div>
                                                    </div>
                                                @elseif(is_array($value))
                                                    <textarea name="content_json[{{ $key }}]" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                                @elseif(is_string($value) && strlen($value) > 80)
                                                    <textarea name="content_json[{{ $key }}]" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">{!! strip_tags($value) !!}</textarea>
                                                @else
                                                    <input type="text" name="content_json[{{ $key }}]" value="{{ is_string($value) ? strip_tags($value) : $value }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- BARRE D'ACTIONS DU BLOC --}}
                            <div class="flex flex-wrap items-center justify-between gap-4 bg-black/20 p-4 rounded-2xl border border-white/5">
                                <div class="flex items-center gap-3">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_hidden" value="1" {{ $block->is_hidden ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white/40 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 peer-checked:after:bg-white"></div>
                                    </label>
                                    <span class="text-[10px] font-bold uppercase tracking-widest {{ $block->is_hidden ? 'text-orange-500' : 'text-white/40' }}">
                                        {{ $block->is_hidden ? 'Masqué' : 'Visible' }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="text-[9px] text-white/10 font-mono italic">ID: #{{ $block->id_block }}</span>
                                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg active:scale-95">
                                        Enregistrer
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            </section>

            {{-- INJECTION DE LA SECTION SITUATIONS / ENJEUX --}}
            @if($isBeforeHome)
                <section class="bg-[#0d0d0f] border border-blue-500/20 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mb-10 shadow-xl shadow-blue-500/5">
                    <div class="bg-blue-600/10 px-6 py-4 border-b border-white/5 flex justify-between items-center">
                        <h2 class="text-blue-400 font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Situations & Vos Enjeux (Éléments Dynamiques)
                        </h2>
                        <span class="bg-blue-600 text-white font-bold text-[9px] px-2.5 py-1 rounded-full uppercase tracking-wider">Gestion Active</span>
                    </div>

                    <div class="p-6 md:p-8 space-y-8">
                        @php
                            $situations = DB::table('page_blocks')->where('id_page', $page->id_page)->where('type', 'situations')->orderBy('position', 'asc')->get();
                        @endphp

                        @if($situations->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($situations as $sit)
                                    @php $sitData = json_decode($sit->content, true); @endphp
                                    <div class="bg-white/5 p-5 rounded-2xl border border-white/5 flex flex-col justify-between hover:border-white/10 transition-colors">
                                        <form action="{{ route('admin.block.update', $sit->id_block) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <div class="flex justify-between items-center border-b border-white/5 pb-2">
                                                <span class="text-[9px] font-mono text-white/30">Enjeu #{{ $sit->id_block }}</span>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="is_hidden" value="1" {{ $sit->is_hidden ? 'checked' : '' }} onchange="this.form.submit()" class="rounded bg-black/40 border-white/10 text-blue-600 focus:ring-0 w-3.5 h-3.5">
                                                    <span class="text-[9px] uppercase tracking-wider text-white/40">{{ $sit->is_hidden ? 'Masqué' : 'Visible' }}</span>
                                                </label>
                                            </div>

                                            <div class="grid grid-cols-4 gap-3">
                                                <div class="col-span-1">
                                                    <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Icône / Émoji</label>
                                                    <input type="text" name="content_json[icon]" value="{{ $sitData['icon'] ?? '📊' }}" class="w-full text-center bg-black/20 border border-white/10 rounded-xl px-2 py-2 text-white text-sm font-bold focus:border-blue-500">
                                                </div>
                                                <div class="col-span-3">
                                                    <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Titre de l'enjeu</label>
                                                    <input type="text" name="content_json[title]" value="{{ $sitData['title'] ?? '' }}" class="w-full bg-black/20 border border-white/10 rounded-xl px-3 py-2 text-white text-xs font-bold focus:border-blue-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Description / Contenu</label>
                                                <textarea name="content_json[description]" rows="3" class="w-full bg-black/20 border border-white/10 rounded-xl px-3 py-2 text-white text-xs focus:border-blue-500">{{ $sitData['description'] ?? '' }}</textarea>
                                            </div>

                                            <div class="pt-2">
                                                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-colors">
                                                    Mettre à jour
                                                </button>
                                            </div>
                                        </form>

                                        <form action="{{ route('admin.block.destroy', $sit->id_block) }}" method="POST" onsubmit="return confirm('Supprimer cet enjeu ?');" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full flex justify-center items-center py-2 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-[10px] font-bold transition-all">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                Supprimer l'enjeu
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Formulaire d'ajout d'enjeu --}}
                        <div class="border-t border-white/5 pt-6">
                            <form action="{{ route('admin.block.storeSituation') }}" method="POST" class="bg-blue-600/5 p-6 rounded-2xl border border-blue-500/10 space-y-4">
                                @csrf
                                <input type="hidden" name="id_page" value="{{ $page->id_page }}">
                                <div class="flex items-center gap-2 text-blue-400 font-bold text-xs uppercase tracking-wider mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Ajouter un nouvel Enjeu / Situation
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="md:col-span-1">
                                        <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Icône (Émoji)</label>
                                        <input type="text" name="icon" required placeholder="📉" value="📊" class="w-full text-center bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Titre</label>
                                        <input type="text" name="title" required placeholder="Ex: Trésorerie tendue" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                    </div>
                                    <div class="md:col-span-4">
                                        <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Description</label>
                                        <input type="text" name="description" required placeholder="Ex: Vous manquez de visibilité..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                    Créer et ajouter l'enjeu
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            @endif
          
        @endforeach

        {{-- SECTION NOTRE MÉTHODE & CARDS (INTERFACE ALPINE.JS DÉDIÉE) --}}
        @php
            $methodes = DB::table('page_blocks')->where('id_page', $page->id_page)->where('type', 'method')->orderBy('position', 'asc')->get();
            $methodTitleBlock = DB::table('page_blocks')->where('id_page', $page->id_page)->where('type', 'method_title')->first();
        @endphp

        @if($methodes->count() > 0 || $methodTitleBlock)
            <section class="bg-[#0d0d0f] border border-indigo-500/20 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mb-10 shadow-xl shadow-indigo-500/5">
                <div class="bg-indigo-600/10 px-6 py-4 border-b border-white/5 flex justify-between items-center">
                    <h2 class="text-indigo-400 font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Gestion de la Section "Notre Méthode"
                    </h2>
                    <span class="bg-indigo-600 text-white font-bold text-[9px] px-2.5 py-1 rounded-full uppercase tracking-wider">Interface Dédiée</span>
                </div>

                <div class="p-6 md:p-8 space-y-10">

                    {{-- 1. EN-TÊTE GLOBAL (method_title) --}}
                    @if($methodTitleBlock)
                        @php
                            $methodTitleData = json_decode($methodTitleBlock->content, true) ?? [];
                        @endphp
                        <form action="{{ route('admin.block.update', $methodTitleBlock->id_block) }}" method="POST" class="bg-white/5 p-6 rounded-2xl border border-white/5 space-y-4">
                            @csrf
                            <div class="flex justify-between items-center">
                                <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Titre et Description de la section (method_title)</h3>
                                <span class="text-[9px] text-white/30 font-mono">ID Bloc: {{ $methodTitleBlock->id_block }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Titre de la section</label>
                                <input type="text" name="content_json[title]" value="{{ $methodTitleData['title'] ?? '' }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Description de la section</label>
                                <textarea name="content_json[description]" rows="2" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white text-xs focus:border-indigo-500">{{ $methodTitleData['description'] ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="py-2.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-colors">
                                Mettre à jour le titre
                            </button>
                        </form>
                    @endif

                    {{-- 2. LISTE DES ÉTAPES ET CARDS (method - ALPINE.JS) --}}
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-white/80 uppercase tracking-wider">Étapes et Cards de la méthode</h3>

                        @if($methodes->count() > 0)
                            <div class="grid grid-cols-1 gap-8">
                                @foreach($methodes as $met)
                                    @php 
                                        $metData = json_decode($met->content, true) ?? [];
                                        $subCards = $metData['cards'] ?? [];
                                        
                                        if (is_string($subCards)) {
                                            $subCards = json_decode($subCards, true) ?? [];
                                        }
                                        if (!is_array($subCards)) {
                                            $subCards = [];
                                        }
                                    @endphp
                                    
                                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 space-y-6 hover:border-indigo-500/30 transition-colors"
                                        x-data="{ 
                                            cards: {{ json_encode(array_values($subCards)) }},
                                            showAddCard: false,
                                            newTitle: '',
                                            newDescription: '',
                                            addCard() {
                                                if(this.newTitle.trim() !== '') {
                                                    this.cards.push({ title: this.newTitle, description: this.newDescription });
                                                    this.newTitle = '';
                                                    this.newDescription = '';
                                                    this.showAddCard = false;
                                                }
                                            }
                                        }">
                                        
                                        <form action="{{ route('admin.block.update', $met->id_block) }}" method="POST" class="space-y-6">
                                            @csrf
                                            
                                            {{-- En-tête de l'étape --}}
                                            <div class="flex justify-between items-center border-b border-white/10 pb-3">
                                                <div class="flex items-center gap-3">
                                                    <span class="bg-indigo-500/20 text-indigo-400 font-mono text-xs px-2.5 py-1 rounded-lg border border-indigo-500/30 font-bold">
                                                        Étape #{{ $metData['step'] ?? $loop->iteration }}
                                                    </span>
                                                    <span class="text-[10px] text-white/40 font-mono">ID Bloc: {{ $met->id_block }}</span>
                                                </div>
                                                
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="is_hidden" value="1" {{ $met->is_hidden ? 'checked' : '' }} onchange="this.form.submit()" class="rounded bg-black/40 border-white/10 text-indigo-600 focus:ring-0 w-3.5 h-3.5">
                                                    <span class="text-[9px] uppercase tracking-wider text-white/40">{{ $met->is_hidden ? 'Masquée' : 'Visible' }}</span>
                                                </label>
                                            </div>

                                            {{-- Titre et Numéro/Émoji --}}
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div class="md:col-span-1">
                                                    <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Émoji / Numéro</label>
                                                    <input type="text" name="content_json[step]" value="{{ $metData['step'] ?? '01' }}" class="w-full text-center bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white text-sm font-bold focus:border-indigo-500">
                                                </div>
                                                <div class="md:col-span-3">
                                                    <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Titre de l'étape</label>
                                                    <input type="text" name="content_json[title]" value="{{ $metData['title'] ?? '' }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white text-xs font-bold focus:border-indigo-500">
                                                </div>
                                            </div>

                                            {{-- Description globale --}}
                                            <div>
                                                <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Description globale de l'étape</label>
                                                <textarea name="content_json[description]" rows="2" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:border-indigo-500">{{ $metData['description'] ?? '' }}</textarea>
                                            </div>

                                            {{-- CARDS DYNAMIQUES (ALPINE.JS) --}}
                                            <div class="bg-black/30 p-4 rounded-xl border border-white/5 space-y-4">
                                                <div class="flex justify-between items-center">
                                                    <h4 class="text-[11px] font-black text-indigo-400 uppercase tracking-wider flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                                        Cards rattachées (<span x-text="cards.length"></span>)
                                                    </h4>
                                                </div>

                                                <div class="space-y-3">
                                                    <template x-for="(card, index) in cards" :key="index">
                                                        <div class="bg-white/5 p-3 rounded-xl border border-white/10 flex flex-col md:flex-row gap-3 items-start md:items-center relative group">
                                                            <div class="flex-1 w-full space-y-2">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-[9px] font-mono text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded" x-text="'Card #' + (index + 1)"></span>
                                                                    <input type="text" 
                                                                        :name="'content_json[cards][' + index + '][title]'" 
                                                                        x-model="card.title" 
                                                                        placeholder="Titre de la card" 
                                                                        class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-1.5 text-white text-xs font-semibold focus:border-indigo-500">
                                                                </div>
                                                                <input type="text" 
                                                                    :name="'content_json[cards][' + index + '][description]'" 
                                                                    x-model="card.description" 
                                                                    placeholder="Description de la card" 
                                                                    class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-1.5 text-white/80 text-xs focus:border-indigo-500">
                                                            </div>

                                                            <button type="button" 
                                                                    @click="cards.splice(index, 1)" 
                                                                    class="flex items-center gap-1 text-rose-400 hover:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 px-3 py-2 rounded-lg transition-colors text-[10px] font-bold uppercase tracking-wider shrink-0">
                                                                Supprimer
                                                            </button>
                                                        </div>
                                                    </template>

                                                    <div x-show="cards.length === 0" class="text-[10px] text-white/30 italic text-center py-2">
                                                        Aucune card enregistrée.
                                                    </div>
                                                </div>

                                                {{-- AJOUT CARD --}}
                                                <div class="pt-3 border-t border-white/5 space-y-3">
                                                    <button type="button" 
                                                            @click="showAddCard = !showAddCard" 
                                                            class="text-[10px] font-bold text-emerald-400 hover:text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 px-3 py-2 rounded-lg transition-colors uppercase tracking-wider flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 transition-transform" :class="showAddCard ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                        <span x-text="showAddCard ? 'Fermer' : '+ Ajouter une card'"></span>
                                                    </button>

                                                    <div x-show="showAddCard" x-transition class="p-3 bg-white/5 rounded-xl border border-emerald-500/30 space-y-3">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                            <input type="text" x-model="newTitle" placeholder="Titre de la nouvelle card" class="bg-black/60 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-emerald-500">
                                                            <input type="text" x-model="newDescription" placeholder="Description de la nouvelle card" class="bg-black/60 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-emerald-500">
                                                        </div>
                                                        <div class="flex justify-end">
                                                            <button type="button" @click="addCard()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold uppercase rounded-lg transition-colors">
                                                                Valider la card
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex justify-end pt-2">
                                                <button type="submit" class="py-2.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-colors">
                                                    Enregistrer les modifications
                                                </button>
                                            </div>
                                        </form>

                                        {{-- FORMULAIRE DE SUPPRESSION DE L'ÉTAPE --}}
                                        <form action="{{ route('admin.block.destroy', $met->id_block) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette étape entière ?');" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full flex justify-center items-center py-2 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-xl text-[10px] font-bold transition-all">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                Supprimer cette étape
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- 3. CRÉATION D'UNE NOUVELLE ÉTAPE --}}
                        <div class="mt-8 border-t border-indigo-500/20 pt-8" x-data="{ cards: [] }">
                            <form action="{{ route('admin.method.store') }}" method="POST" class="bg-indigo-950/20 border border-indigo-500/30 p-6 rounded-2xl space-y-6">
                                @csrf
                                <input type="hidden" name="id_page" value="{{ $page->id_page }}">
                                
                                <div class="flex items-center gap-2">
                                    <span class="bg-emerald-500/20 text-emerald-400 p-1.5 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </span>
                                    <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Ajouter une nouvelle étape</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Numéro / Émoji</label>
                                        <input type="text" name="step" placeholder="03" class="w-full text-center bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white text-xs focus:border-indigo-500">
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Titre de l'étape</label>
                                        <input type="text" name="title" required placeholder="ex: Analyse approfondie" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:border-indigo-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-bold text-white/40 uppercase mb-1">Description principale</label>
                                    <textarea name="description" rows="2" placeholder="Description de l'étape..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:border-indigo-500"></textarea>
                                </div>

                                <div class="bg-black/30 p-4 rounded-xl border border-white/10 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Cards de cette étape</span>
                                        <button type="button" @click="cards.push({title: '', description: ''})" class="py-1.5 px-3 bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 border border-indigo-500/30 text-[10px] font-bold uppercase rounded-lg transition-all">
                                            + Ajouter une card
                                        </button>
                                    </div>

                                    <template x-for="(card, index) in cards" :key="index">
                                        <div class="bg-white/5 p-3 rounded-xl border border-white/10 relative space-y-2">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-[9px] font-bold text-white/50 uppercase" x-text="'Card #' + (index + 1)"></span>
                                                <button type="button" @click="cards.splice(index, 1)" class="text-rose-400 hover:text-rose-300 text-[10px] font-bold uppercase">
                                                    Supprimer
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <input type="text" :name="'cards[' + index + '][title]'" x-model="card.title" placeholder="Titre" class="bg-black/50 border border-white/10 rounded-lg px-3 py-1.5 text-white text-xs focus:border-indigo-500">
                                                <input type="text" :name="'cards[' + index + '][description]'" x-model="card.description" placeholder="Description" class="bg-black/50 border border-white/10 rounded-lg px-3 py-1.5 text-white text-xs focus:border-indigo-500">
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-colors">
                                    + Créer cette nouvelle étape
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </section>
        @endif
        {{-- SECTION TARIF (Placée après la méthode) --}}
        <div class="space-y-8 bg-[#0d0d0f] border border-white/5 p-6 md:p-8 rounded-[2.5rem]">
            <div class="border-b border-white/5 pb-4">
                <h2 class="text-blue-500 font-black uppercase tracking-widest text-xs">Administration de la section Tarifs</h2>
                <p class="text-white/60 text-xs mt-1">Modifiez les champs ci-dessous en toute simplicité.</p>
            </div>

            @php
                $tarifBlocks = \App\Models\PageBlock::whereIn('type', [
                    'tarif_title', 
                    'tarif_title_card_01', 
                    'tarif_card_01', 
                    'tarif_card_02'
                ])->orderBy('position')->get();
            @endphp

            @foreach($tarifBlocks as $block)
                @php 
                    $data = json_decode($block->content, true); 
                    $titlesMap = [
                        'tarif_title' => 'En-tête principal (Nos Tarifs)',
                        'tarif_title_card_01' => 'Sous-titre / Phase 1 (Audit)',
                        'tarif_card_01' => 'Carte Tarifaire (TPE / PME / ETI)',
                        'tarif_card_02' => 'Bloc Phase 2 (Accompagnement)'
                    ];
                @endphp

                <form action="{{ route('admin.block.update', $block->id_block) }}" method="POST" class="bg-black/30 border border-white/5 p-6 rounded-2xl space-y-4">
                    @csrf
                    
                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                        <span class="text-xs font-bold text-white uppercase tracking-wider">
                            {{ $titlesMap[$block->type] ?? $block->type }} 
                            <span class="text-white/30 font-mono ml-2">#{{ $block->id_block }}</span>
                        </span>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Enregistrer
                        </button>
                    </div>

                    @if(is_array($data))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($data as $key => $value)
                                @if($key == 'is_best') @continue @endif

                                {{-- On nettoie les entités HTML bizarres (&nbsp;, etc.) pour l'affichage dans l'input --}}
                                @php 
                                    $cleanValue = is_string($value) ? html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8') : $value; 
                                @endphp

                                <div class="{{ is_array($value) || (is_string($value) && strlen($value) > 80) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">
                                        {{ str_replace('_', ' ', $key) }}
                                    </label>

                                    {{-- Cas d'une liste : chaque élément a sa propre ligne dédiée --}}
                                    @if(is_array($value))
                                        <div class="space-y-2 p-4 bg-black/20 rounded-xl border border-white/5" id="list-container-{{ $block->id_block }}-{{ $key }}">
                                            @foreach($value as $index => $item)
                                                <div class="flex items-center gap-2">
                                                    <input type="text" name="content_json[{{ $key }}][]" value="{{ html_entity_decode(strip_tags($item), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-blue-500">
                                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300 px-2 py-1 text-xs font-bold">×</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" onclick="addListItem('{{ $block->id_block }}', '{{ $key }}')" class="mt-2 text-[10px] text-blue-400 hover:text-blue-300 font-bold uppercase tracking-wider block">
                                            + Ajouter une ligne
                                        </button>

                                    {{-- Cas d'un texte long --}}
                                    @elseif(is_string($value) && strlen($value) > 80)
                                        <textarea name="content_json[{{ $key }}]" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">{{ $cleanValue }}</textarea>

                                    {{-- Cas d'un texte court --}}
                                    @else
                                        <input type="text" name="content_json[{{ $key }}]" value="{{ $cleanValue }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>
            @endforeach
        </div>

        {{-- Petit script JS pour ajouter dynamiquement une ligne de liste si l'admin clique sur le bouton --}}
        <script>
        function addListItem(blockId, key) {
            const container = document.getElementById(`list-container-${blockId}-${key}`);
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="text" name="content_json[${key}][]" value="" placeholder="Nouvel élément..." class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-blue-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300 px-2 py-1 text-xs font-bold">×</button>
            `;
            container.appendChild(div);
        }
        </script>

        <div class="space-y-8 bg-[#0d0d0f] border border-white/5 p-6 md:p-8 rounded-[2.5rem] mt-8">
            <div class="border-b border-white/5 pb-4">
                <h2 class="text-blue-500 font-black uppercase tracking-widest text-xs">Administration de la section "Qui sommes-nous"</h2>
                <p class="text-white/60 text-xs mt-1">Modifiez les textes, les statistiques et les arguments de la section sans code.</p>
            </div>

            @php
                // Récupération des blocs de la section "Qui sommes-nous"
                $aboutBlocks = \App\Models\PageBlock::whereIn('type', [
                    'us_company', 
                    'details_company', 
                    'card_company'
                ])->orderBy('position')->get();
            @endphp

            @foreach($aboutBlocks as $block)
                @php 
                    $data = json_decode($block->content, true); 
                    $titlesMap = [
                        'us_company' => 'En-tête et Textes principaux (Qui sommes-nous)',
                        'details_company' => 'Bloc de droite (Pourquoi nous, pas un autre ?)',
                        'card_company' => 'Carte / Statistique (Chiffre & Label)'
                    ];
                @endphp

                <form action="{{ route('admin.block.update', $block->id_block) }}" method="POST" class="bg-black/30 border border-white/5 p-6 rounded-2xl space-y-4">
                    @csrf
                    
                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                        <span class="text-xs font-bold text-white uppercase tracking-wider">
                            {{ $titlesMap[$block->type] ?? $block->type }} 
                            <span class="text-white/30 font-mono ml-2">#{{ $block->id_block }}</span>
                        </span>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Enregistrer
                        </button>
                    </div>

                    @if(is_array($data))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($data as $key => $value)
                                @php 
                                    $cleanValue = is_string($value) ? html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8') : $value; 
                                @endphp

                                <div class="{{ is_array($value) || (is_string($value) && strlen($value) > 80) ? 'md:col-span-2' : '' }}">
                                    <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">
                                        {{ str_replace('_', ' ', $key) }}
                                    </label>

                                    {{-- Cas d'un tableau (paragraphes multiples ou liste d'arguments) : Lignes séparées --}}
                                    @if(is_array($value))
                                        <div class="space-y-2 p-4 bg-black/20 rounded-xl border border-white/5" id="list-container-about-{{ $block->id_block }}-{{ $key }}">
                                            @foreach($value as $item)
                                                <div class="flex items-center gap-2">
                                                    <input type="text" name="content_json[{{ $key }}][]" value="{{ html_entity_decode(strip_tags($item), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-blue-500">
                                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300 px-2 py-1 text-xs font-bold">×</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" onclick="addAboutListItem('{{ $block->id_block }}', '{{ $key }}')" class="mt-2 text-[10px] text-blue-400 hover:text-blue-300 font-bold uppercase tracking-wider block">
                                            + Ajouter une ligne
                                        </button>

                                    {{-- Cas d'un texte long --}}
                                    @elseif(is_string($value) && strlen($value) > 80)
                                        <textarea name="content_json[{{ $key }}]" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">{{ $cleanValue }}</textarea>

                                    {{-- Cas d'un texte court / input simple --}}
                                    @else
                                        <input type="text" name="content_json[{{ $key }}]" value="{{ $cleanValue }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>
            @endforeach
        </div>

        {{-- Script JS pour l'ajout dynamique de lignes (partagé ou spécifique) --}}
        <script>
        function addAboutListItem(blockId, key) {
            const container = document.getElementById(`list-container-about-${blockId}-${key}`);
            if (!container) return;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="text" name="content_json[${key}][]" value="" placeholder="Nouvel élément..." class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white text-xs focus:border-blue-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300 px-2 py-1 text-xs font-bold">×</button>
            `;
            container.appendChild(div);
        }
        </script>
        

    </div>
</div>

{{-- SCRIPT D'INITIALISATION DES ÉDITEURS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('quill_mail_editor')) {
            var quillMail = new Quill('#quill_mail_editor', {
                theme: 'snow',
                modules: { toolbar: [['bold', 'italic', 'underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['clean']] }
            });
            quillMail.on('text-change', function() {
                document.getElementById('mail_content_db').value = quillMail.root.innerHTML;
            });
        }

        document.querySelectorAll('.quill-dynamic-editor').forEach(function(editorContainer) {
            var targetId = editorContainer.getAttribute('data-target');
            var hiddenTextArea = document.getElementById(targetId);
            
            if(hiddenTextArea) {
                var quillInstance = new Quill(editorContainer, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'color': [] }],
                            ['clean']
                        ]
                    }
                });

                quillInstance.on('text-change', function() {
                    hiddenTextArea.value = quillInstance.root.innerHTML;
                });
            }
        });
    });
</script>
@endsection