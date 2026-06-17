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
        {{-- SECTION GESTION DES LOGOS --}}
        <div class="space-y-10">
            @php
                $logoPcData = DB::table('page_blocks')->where('id_block', 47)->first();
                $logoMobileData = DB::table('page_blocks')->where('id_block', 48)->first();
                $logoIconData = DB::table('page_blocks')->where('id_block', 49)->first();
                
                $logoPc = $logoPcData ? ltrim($logoPcData->image_path, '/') : 'images/logo_armature.png';
                $logoMobile = $logoMobileData ? ltrim($logoMobileData->image_path, '/') : 'images/logo_favicon.png';
                $logoIcon = $logoIconData ? ltrim($logoIconData->image_path, '/') : 'images/logo_favicon.png';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Formulaire Logo PC (ID 47) --}}
                <form action="{{ route('admin.logo.update', 47) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white/5 p-6 rounded-3xl border border-white/5">
                    @csrf
                    <label class="block text-[10px] font-bold text-blue-400 uppercase tracking-widest">Logo Ordinateur (Horizontal)</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-black/40 rounded-xl flex items-center justify-center p-2 border border-white/10 overflow-hidden">
                            @if($logoPc)
                                <img src="{{ asset($logoPc) }}?v={{ time() }}" alt="Logo PC" class="max-w-full max-h-full object-contain">
                            @else
                                <span class="text-[8px] text-white/20 text-center">Aucun logo PC</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image_path" required class="w-full text-xs text-white/40 file:bg-blue-600 file:border-0 file:text-white file:rounded-lg file:px-3 file:py-2 file:mr-3 cursor-pointer">
                            <button type="submit" class="mt-4 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold uppercase shadow-lg">Mettre à jour PC</button>
                        </div>
                    </div>
                </form>

                {{-- Formulaire Logo Mobile (ID 48) --}}
                <form action="{{ route('admin.logo.update', 48) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white/5 p-6 rounded-3xl border border-white/5">
                    @csrf
                    <label class="block text-[10px] font-bold text-blue-400 uppercase tracking-widest">Logo Mobile (Compact)</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-black/40 rounded-xl flex items-center justify-center p-2 border border-white/10 overflow-hidden">
                            @if($logoMobile)
                                <img src="{{ asset($logoMobile) }}?v={{ time() }}" alt="Logo Mobile" class="max-w-full max-h-full object-contain">
                            @else
                                <span class="text-[8px] text-white/20 text-center">Aucun logo Mobile</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image_path" required class="w-full text-xs text-white/40 file:bg-blue-600 file:border-0 file:text-white file:rounded-lg file:px-3 file:py-2 file:mr-3 cursor-pointer">
                            <button type="submit" class="mt-4 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold uppercase shadow-lg">Mettre à jour Mobile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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

        {{-- BOUCLE PRINCIPALE DES SECTIONS --}}
        @foreach($blocks as $type => $group)
            @php 
                $ignoredSections = ['carousel', 'hero', 'text', 'text_image', 'container_marketing', 'featurette', 'featurette-divider', 'situations', 'goals', 'video_presentation', 'meet_goals'];
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
                                                        <textarea id="hidden_{{ $block->id_block }}_{{ $key }}" name="content_json[{{ $key }}]" class="hidden">{!! $value !!}</textarea>
                                                        <div class="quill-dynamic-editor bg-white/5 rounded-xl border border-white/10 text-white" data-target="hidden_{{ $block->id_block }}_{{ $key }}" style="height: 120px;">
                                                            {!! $value !!}
                                                        </div>
                                                    </div>
                                                @elseif(strlen($value) > 80)
                                                    <textarea name="content_json[{{ $key }}]" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">{!! strip_tags($value) !!}</textarea>
                                                @else
                                                    <input type="text" name="content_json[{{ $key }}]" value="{{ strip_tags($value) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- BARRE D'ACTIONS DU BLOC (Isolée de la grid) --}}
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