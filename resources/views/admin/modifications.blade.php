@extends('layouts.admin')

@section('admin_content')
<div class="p-4 md:p-8">
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
        @foreach($blocks as $type => $group)
            {{-- FILTRE DES SECTIONS À SUPPRIMER --}}
            @php 
                $ignoredSections = ['carousel', 'hero', 'text', 'text_image', 'container_marketing', 'featurette', 'featurette-divider'];
                if(in_array(strtolower($type), $ignoredSections)) continue;
            @endphp

            <section class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] md:rounded-[2.5rem] overflow-hidden">
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

                            {{-- MÉDIAS --}}
                            <div class="space-y-6">
                                @if(!$isBeforeHome && !$isFAQ)
                                    <div class="bg-white/5 p-5 rounded-2xl border border-white/10">
                                        <p class="text-[10px] font-bold text-white/40 uppercase mb-4">Médias</p>
                                        
                                        {{-- Image Path avec Cache Busting --}}
                                        <div class="mb-6">
                                            <label class="text-[9px] text-blue-400 font-bold block mb-2 uppercase italic">Photo / Miniature</label>
                                            @if($block->image_path && file_exists(public_path($block->image_path)))
                                                <div class="relative w-20 h-20 mb-3">
                                                    {{-- L'astuce est ici : ?v=timestamp --}}
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
                                                            {{-- On ajoute le paramètre version ici aussi --}}
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
                                        @endif
                                    </div>
                                @endif

                                <div class="flex justify-end gap-4 items-center">
                                    <span class="text-[9px] text-white/10 font-mono italic">Block #{{ $block->id_block }}</span>
                                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-blue-500/20 active:scale-95">
                                        Enregistrer
                                    </button>
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
    document.querySelectorAll('textarea').forEach(textarea => {
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