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
            {{-- 1. EXTRACTION DES LOGOS DEPUIS $blocks --}}
            @php
                $logoPcData = DB::table('page_blocks')->where('id_block', 47)->first();
                $logoMobileData = DB::table('page_blocks')->where('id_block', 48)->first();
                $logoIconData = DB::table('page_blocks')->where('id_block', 49)->first();
                
                // On définit un fallback (image par défaut) si rien n'est trouvé en BDD
                $logoPc = $logoPcData ? ltrim($logoPcData->image_path, '/') : 'images/logo_armature.png';
                $logoMobile = $logoMobileData ? ltrim($logoMobileData->image_path, '/') : 'images/logo_favicon.png';
                $logoIcon = $logoIconData ? ltrim($logoIconData->image_path, '/') : 'images/logo_favicon.png';
            @endphp

            {{-- 2. SECTION GESTION DES LOGOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Formulaire Logo PC (ID 47) --}}
                <form action="{{ route('admin.logo.update', 47) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white/5 p-6 rounded-3xl border border-white/5">
                    @csrf
                    <label class="block text-[10px] font-bold text-blue-400 uppercase tracking-widest">Logo Ordinateur (Horizontal)</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-black/40 rounded-xl flex items-center justify-center p-2 border border-white/10 overflow-hidden">
                            @if($logoPc)
                                <img src="{{ asset($logoPc) }}?v={{ time() }}" 
                                    alt="Logo PC" class="max-w-full max-h-full object-contain">
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
                                <img src="{{ asset($logoMobile) }}?v={{ time() }}"  
                                    alt="Logo Mobile" class="max-w-full max-h-full object-contain">
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

                <form action="{{ route('admin.logo.update', 49) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white/5 p-6 rounded-3xl border border-white/5">
                    @csrf
                    <label class="block text-[10px] font-bold text-blue-400 uppercase tracking-widest">Icon site (Compact)</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-black/40 rounded-xl flex items-center justify-center p-2 border border-white/10 overflow-hidden">
                            @if($logoIcon)
                                <img src="{{ asset($logoIcon) }}?v={{ time() }}"  
                                    alt="Logo Mobile" class="max-w-full max-h-full object-contain">
                            @else
                                <span class="text-[8px] text-white/20 text-center">Aucune icon</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image_path" required class="w-full text-xs text-white/40 file:bg-blue-600 file:border-0 file:text-white file:rounded-lg file:px-3 file:py-2 file:mr-3 cursor-pointer">
                            <button type="submit" class="mt-4 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold uppercase shadow-lg">Mettre à jour Icon</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SECTION GESTION DES COULEURS COMPLETE --}}
            @php
                // On récupère toutes les couleurs liées à la page 6
                $colors = DB::table('page_blocks')
                            ->where('id_page', 6)
                            ->where(function($q) {
                                $q->where('type', 'like', 'color_%')
                                ->orWhereIn('type', ['header_color', 'main_01_color']);
                            })->get();
                
                // Fonction helper pour trouver le bon bloc ou renvoyer un objet vide pour éviter les erreurs
                function getThemeBlock($colors, $type) {
                    return $colors->where('type', $type)->first() ?? (object)['id_block' => 0, 'content' => '#000000'];
                }

                $config = [
                    ['type' => 'color_primary',    'label' => 'Accent (Boutons, Liens)', 'desc' => 'Couleur vive pour l\'action.', 'default' => '#3b82f6'],
                    ['type' => 'color_bg_dark',    'label' => 'Fond de Page', 'desc' => 'Couleur de fond principale du site.', 'default' => '#020617'],
                    ['type' => 'color_bg_card',    'label' => 'Cartes & Éléments', 'desc' => 'Fond des cartes, inputs et menus.', 'default' => '#1a1a1c'],
                    ['type' => 'color_text_light', 'label' => 'Texte Principal', 'desc' => 'Titres et textes importants (blanc).', 'default' => '#ffffff'],
                    ['type' => 'color_text_gray',  'label' => 'Texte Secondaire', 'desc' => 'Descriptions et textes d\'aide (gris).', 'default' => '#94a3b8'],
                    ['type' => 'color_border',     'label' => 'Bordures & Lignes', 'desc' => 'Lignes de séparation et contours.', 'default' => '#ffffff1a'],
                ];
            @endphp

            <div class="mt-8 bg-[#0d0d0f] border border-white/5 rounded-[2rem] overflow-hidden">
                <div class="bg-white/5 px-6 py-4 border-b border-white/5 flex items-center gap-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <h2 class="text-blue-500 font-black uppercase tracking-widest text-[10px]">Palette de Couleurs Dynamique</h2>
                </div>
                
                <div class="p-6 md:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($config as $item)
                        @php $block = getThemeBlock($colors, $item['type']); @endphp
                        
                        <form action="{{ route('admin.block.updateColor', $block->id_block) }}" method="POST" class="group space-y-3 bg-white/5 p-5 rounded-3xl border border-white/5 hover:border-blue-500/30 transition-all">
                            @csrf
                            <div class="flex justify-between items-start">
                                <label class="block text-[10px] font-bold text-white/60 uppercase tracking-tighter">{{ $item['label'] }}</label>
                                <span class="text-[8px] text-white/20 font-mono">#{{ $block->id_block }}</span>
                            </div>

                            <div class="flex items-center gap-4 bg-black/20 p-2 rounded-2xl border border-white/5">
                                {{-- Le sélecteur visuel --}}
                                <div class="relative w-12 h-12 shrink-0">
                                    <input type="color" 
                                        value="{{ substr($block->content, 0, 7) }}" 
                                        oninput="this.nextElementSibling.value = this.value.toUpperCase(); this.closest('form').querySelector('.hex-input').value = this.value.toUpperCase();"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="w-full h-full rounded-xl border border-white/20 shadow-inner" style="background-color: {{ $block->content }}"></div>
                                </div>
                                
                                {{-- Le champ texte pour le code HEX (utilisé pour l'envoi) --}}
                                <input type="text" 
                                    name="content" 
                                    value="{{ $block->content }}" 
                                    class="hex-input flex-1 bg-transparent border-0 text-white font-mono text-sm focus:ring-0 p-0"
                                    placeholder="#FFFFFF">
                            </div>

                            <p class="text-[9px] text-white/30 italic leading-relaxed px-1">{{ $item['desc'] }}</p>
                            
                            <button type="submit" class="w-full py-2.5 bg-blue-600/10 group-hover:bg-blue-600 text-blue-500 group-hover:text-white rounded-xl text-[10px] font-black transition-all uppercase tracking-widest active:scale-95">
                                Mettre à jour
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            <script>
                document.querySelectorAll('input[type="color"]').forEach(picker => {
                    picker.addEventListener('input', function() {
                        // 1. On met à jour le texte hexadécimal à côté
                        const inputTexte = this.closest('form').querySelector('.hex-input');
                        inputTexte.value = this.value.toUpperCase();
                        
                        // 2. On change la couleur de l'aperçu visuel (le carré)
                        const preview = this.nextElementSibling;
                        preview.style.backgroundColor = this.value;
                    });
                });

                document.querySelectorAll('.hex-input').forEach(input => {
                    input.addEventListener('input', function() {
                        let val = this.value;
                        if(!val.startsWith('#')) val = '#' + val;
                        
                        if(/^#[0-9A-F]{6}$/i.test(val)) {
                            const picker = this.closest('form').querySelector('input[type="color"]');
                            const preview = picker.nextElementSibling;
                            picker.value = val;
                            preview.style.backgroundColor = val;
                        }
                    });
                });
            </script>
        
        {{-- SECTION ÉDITION DU MAIL DE CONFIRMATION --}}
        @php
            $mailBlock = DB::table('page_blocks')->where('type', 'email_audit_confirmation')->first();
        @endphp

        @if($mailBlock)
        <section class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mb-12">
            <div class="bg-white/5 px-6 py-4 border-b border-white/5 flex justify-between items-center">
                <h2 class="text-emerald-500 font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Contenu du Mail de Confirmation
                </h2>
                <span class="text-[9px] text-white/20 font-mono italic text-right">ID Bloc: #{{ $mailBlock->id_block }}</span>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Gauche : Instructions --}}
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

                {{-- Droite : L'éditeur UNIQUE --}}
                <div class="lg:col-span-2">
                    <form action="{{ route('admin.block.updateMail', $mailBlock->id_block) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            {{-- On ajoute la classe 'ignore-quill-global' pour que ton script du bas ne le touche pas --}}
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var quillMail = new Quill('#quill_mail_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['clean']
                        ]
                    }
                });

                // Crucial : On synchronise le contenu vers le textarea pour la sauvegarde
                quillMail.on('text-change', function() {
                    document.getElementById('mail_content_db').value = quillMail.root.innerHTML;
                });
            });
        </script>
        @endif

        @foreach($blocks as $type => $group)
    {{-- FILTRE DES SECTIONS À SUPPRIMER --}}
    @php 
        $ignoredSections = ['carousel', 'hero', 'text', 'text_image', 'container_marketing', 'featurette', 'featurette-divider'];
        if(in_array(strtolower($type), $ignoredSections)) continue;
    @endphp

    <section class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden mb-10">
        <div class="bg-white/5 px-6 py-4 border-b border-white/5 flex justify-between items-center">
            <h2 class="text-blue-500 font-black uppercase tracking-widest text-[10px] md:text-xs">
                {{ strtoupper(str_replace('_', ' ', $type)) }}
            </h2>
        </div>

        <div class="p-6 md:p-8 space-y-12">
            @foreach($group as $block)
                            @php 
                                $data = json_decode($block->content, true); 
                                $isBeforeHome = (strtolower($type) == 'before_home');
                                $isFAQ = (strtolower($type) == 'faq');
                                $isVideo = (strtolower($type) == 'video_presentation');
                            @endphp
                            
                            <form action="{{ route('admin.block.update', $block->id_block) }}" method="POST" enctype="multipart/form-data" 
                                class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-10 border-b border-white/5 last:border-0 last:pb-0 transition-all rounded-3xl {{ session('last_updated') == $block->id_block ? 'ring-1 ring-blue-500/30 bg-blue-500/5 p-4' : '' }}">
                                @csrf
                                
                                {{-- TEXTES --}}
                                <div class="space-y-5">
                                    @if(is_array($data))
                                        @foreach($data as $key => $value)
                                            @if(in_array($key, ['button_secondary_text', 'button_secondary_link', 'video_link'])) @continue @endif

                                            <div>
                                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">{{ str_replace('_', ' ', $key) }}</label>
                                                @if(strlen($value) > 80)
                                                    <textarea name="content_json[{{ $key }}]" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">{{ $value }}</textarea>
                                                @else
                                                    <input type="text" name="content_json[{{ $key }}]" value="{{ $value }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500">
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                {{-- MÉDIAS & BOUTON D'ENREGISTREMENT --}}
                                <div class="space-y-6 flex flex-col justify-between">
                                    @if(!$isBeforeHome && !$isFAQ)
                                        <div class="bg-white/5 p-5 rounded-2xl border border-white/10">
                                            <p class="text-[10px] font-bold text-white/40 uppercase mb-4">Médias</p>
                                            
                                            {{-- Image Path avec Cache Busting --}}
                                            <div class="mb-6">
                                                <label class="text-[9px] text-blue-400 font-bold block mb-2 uppercase italic">Photo / Miniature</label>
                                                @if($block->image_path && file_exists(public_path($block->image_path)))
                                                    <div class="relative w-20 h-20 mb-3">
                                                        <img src="{{ asset($block->image_path) }}?v={{ filemtime(public_path($block->image_path)) }}" 
                                                            class="w-full h-full object-cover rounded-lg border border-white/20 shadow-lg">
                                                    </div>
                                                @endif
                                                <input type="file" name="image_path" class="w-full text-[10px] text-white/40 file:bg-white/10 file:border-0 file:text-white file:rounded-lg file:px-3 file:mr-3 cursor-pointer">
                                            </div>

                                            @if($isVideo)
                                                <div class="pt-4 border-t border-white/5">
                                                    <label class="text-[9px] text-blue-400 font-bold block mb-2 uppercase italic">Fichier Vidéo (MP4)</label>
                                                    
                                                    @if($block->video_path && file_exists(public_path($block->video_path)))
                                                        <div class="mb-3">
                                                            <video width="200" controls class="rounded-lg border border-white/10">
                                                                <source src="{{ asset($block->video_path) }}?v={{ filemtime(public_path($block->video_path)) }}" type="video/mp4">
                                                                Votre navigateur ne supporte pas la vidéo.
                                                            </video>
                                                        </div>
                                                    @endif

                                                    <div class="text-[10px] text-white/30 mb-2 truncate bg-black/20 p-2 rounded">
                                                        Fichier : {{ basename($block->video_path) }}
                                                    </div>
                                                    <input type="file" name="video_path" class="w-full text-[10px] text-white/40">
                                                </div>
                                            ]@endif
                                        </div>
                                    @endif

                                    {{-- BARRE D'ACTIONS DU BLOC (SWITCH + ENREGISTRER) --}}
                                    <div class="flex flex-wrap items-center justify-between gap-4 bg-black/20 p-4 rounded-2xl border border-white/5 mt-auto">
                                        {{-- SWITCH IS_HIDDEN --}}
                                        <div class="flex items-center gap-3">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_hidden" value="1" {{ $block->is_hidden ? 'checked' : '' }} class="sr-only peer">
                                                <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white/40 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 peer-checked:after:bg-white"></div>
                                            </label>
                                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $block->is_hidden ? 'text-orange-500' : 'text-white/40' }}">
                                                {{ $block->is_hidden ? 'Masqué sur le site' : 'Visible' }}
                                            </span>
                                        </div>

                                        {{-- BOUTON SOUVEGARDE --}}
                                        <div class="flex items-center gap-3">
                                            <span class="text-[9px] text-white/10 font-mono italic">ID: #{{ $block->id_block }}</span>
                                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-blue-500/20 active:scale-95">
                                                Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </section>
            @endforeach
    </div>
</div>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    document.querySelectorAll('textarea:not(.ignore-quill-global)').forEach(textarea => {
        // 1. On crée un container pour Quill juste au-dessus du textarea
        const container = document.createElement('div');
        container.style.height = '200px';
        container.style.backgroundColor = '#1a1a1c'; // Couleur sombre pour matcher ton admin
        container.style.color = 'white';
        textarea.parentNode.insertBefore(container, textarea);

        // 2. On cache le textarea original (mais on le garde pour le formulaire)
        textarea.style.display = 'none';

        // 3. Initialisation de Quill
        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // 4. On charge le contenu actuel
        quill.root.innerHTML = textarea.value;

        // 5. À chaque modification, on met à jour le textarea caché pour Laravel
        quill.on('text-change', function() {
            textarea.value = quill.root.innerHTML;
        });
    });
</script>

<style>
    /* Customisation pour que Quill s'intègre bien dans ton thème noir */
    .ql-toolbar.ql-snow {
        border-color: rgba(255,255,255,0.1);
        background-color: #252529;
        border-radius: 12px 12px 0 0;
    }
    .ql-container.ql-snow {
        border-color: rgba(255,255,255,0.1);
        border-radius: 0 0 12px 12px;
        font-family: inherit;
    }
    .ql-editor.ql-blank::before { color: rgba(255,255,255,0.2); }
    .ql-snow .ql-stroke { stroke: #fff; }
    .ql-snow .ql-fill { fill: #fff; }
</style>
@endsection