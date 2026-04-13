@extends('layouts.admin')

@section('admin_content')
<div class="p-4 md:p-8">

{{-- MESSAGE DE SUCCÈS --}}
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-2xl flex items-center gap-4 animate-bounce-short">
            <div class="p-2 bg-emerald-500 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-emerald-400 font-bold text-sm uppercase tracking-wider">Bravo, c'est un succès !</p>
                <p class="text-emerald-400/60 text-xs">Vous avez créé un nouvel article avec succès.</p>
            </div>
        </div>
    @endif

    @if(session('success_edit'))
        <div class="mb-8 p-4 bg-blue-500/20 border border-blue-500/50 rounded-2xl flex items-center gap-4">
            <div class="p-2 bg-blue-500 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-blue-400 font-bold text-sm uppercase tracking-wider">Modification réussie !</p>
                <p class="text-blue-400/60 text-xs">{{ session('success_edit') }}</p>
            </div>
        </div>
    @endif

    @if(session('newsletter_sent'))
        <div class="mb-8 p-4 bg-blue-500/20 border border-blue-500/50 rounded-2xl flex items-center gap-4">
            <div class="p-2 bg-blue-500 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <div>
                <p class="text-blue-400 font-bold text-sm uppercase tracking-wider">Envoi validé !</p>
                <p class="text-blue-400/60 text-xs">{{ session('newsletter_sent') }}</p>
            </div>
        </div>
    @endif

    {{-- MESSAGE D'ERREUR --}}
    @if($errors->any())
        <div class="mb-8 p-4 bg-red-500/20 border border-red-500/50 rounded-2xl flex items-center gap-4">
            <div class="p-2 bg-red-500 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-red-400 font-bold text-sm uppercase">{{ $errors->first() }}</p>
        </div>
    @endif


    <div class="mb-10 flex justify-between items-center bg-[#0d0d0f] border border-white/5 p-6 rounded-[2rem]">
        <h1 class="text-2xl font-black text-white uppercase tracking-tighter">Blog <span class="text-blue-500">Panel</span></h1>
        
        <button type="button" onclick="toggleCreateForm()" class="group flex items-center gap-3 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold uppercase text-[10px] tracking-widest transition-all shadow-lg shadow-blue-500/20">
            <span id="toggle-text">Nouvel Article</span>
            <svg id="toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </div>

    <div id="create-article-container" class="hidden opacity-0 translate-y-4 transition-all duration-500 ease-out mb-12">
        <form id="blog-form" action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-10 flex justify-between items-center">
                <h1 class="text-2xl font-black text-white uppercase">Nouvel <span class="text-blue-500">Article</span></h1>
                <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-blue-500/20">
                    Publier l'article
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- COLONNE GAUCHE : CONTENU --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] p-6 md:p-8">
                        <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">Titre de l'article</label>
                        <input type="text" name="title" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white text-lg focus:border-blue-500 outline-none mb-6">

                        <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">Contenu de l'article</label>
                        {{-- Container pour Quill --}}
                        <div id="editor-container" class="bg-white/5 border border-white/10 rounded-xl text-white min-h-[500px]"></div>
                        {{-- Champ caché pour envoyer le HTML à Laravel --}}
                        <input type="hidden" name="description" id="description_input">

                        {{-- BLOC IMPORTATION DESIGN : BOUTON UNIQUE --}}
                        <div class="mt-6 p-4 bg-white/5 border border-white/10 rounded-2xl">
                            <p class="text-[10px] font-bold text-white/40 uppercase mb-4 tracking-wider text-center">Importer du contenu</p>
                            
                            {{-- Input invisible acceptant les deux formats --}}
                            <input type="file" id="universal-import" accept=".docx, .pdf" class="hidden">

                            {{-- Bouton Unique Style "Fichier Word" --}}
                            <button type="button" onclick="document.getElementById('universal-import').click()" 
                                class="w-full flex flex-col items-center justify-center p-6 rounded-xl bg-blue-500/5 border border-blue-500/20 hover:bg-blue-500/10 hover:border-blue-500/40 transition-all group">
                                <svg class="w-8 h-8 text-blue-400 mb-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-[12px] font-black text-blue-300 uppercase tracking-widest">Charger un document</span>
                                <span class="text-[9px] text-white/20 uppercase mt-1">Word (.docx) ou PDF (.pdf)</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- COLONNE DROITE : OPTIONS & IMAGE DE COUVERTURE --}}
                <div class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] p-6">
                    <label class="block text-[10px] font-bold text-white/40 uppercase mb-4">Image de couverture</label>
            
                    <div class="relative group">
                        {{-- Zone de prévisualisation --}}
                        <div id="preview-container" class="hidden mb-4 relative rounded-2xl overflow-hidden border border-white/10">
                            <img id="image-preview" src="#" alt="Aperçu" class="w-full h-48 object-cover">
                            <button type="button" onclick="removeImage()" class="absolute top-2 right-2 p-2 bg-red-500 rounded-full text-white hover:bg-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Input stylisé --}}
                        <label id="upload-label" for="main_picture" class="flex flex-col items-center justify-center border-2 border-dashed border-white/10 rounded-2xl p-8 cursor-pointer hover:border-blue-500/50 hover:bg-blue-500/5 transition-all">
                            <svg class="w-8 h-8 text-white/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                            <span class="text-white/40 text-xs font-medium">Choisir une image</span>
                            <input type="file" name="main_picture" id="main_picture" class="hidden" accept="image/*" onchange="previewFile()">
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- FORMULAIRE DE MODIFICATION (Caché par défaut) --}}
    <div id="edit-article-container" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm opacity-0 transition-all duration-300">
        <div class="bg-[#0d0d0f] border border-white/10 rounded-[2.5rem] w-full max-w-5xl max-h-[90vh] overflow-y-auto p-6 md:p-10 shadow-2xl relative translate-y-10 transition-transform duration-300" id="edit-modal-content">
        
            <form id="edit-blog-form" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-8 flex justify-between items-center sticky top-0 bg-[#0d0d0f] z-10 pb-4 border-b border-white/5">
                    <h1 class="text-2xl font-black text-white uppercase">Modifier <span class="text-blue-500">l'Article</span></h1>
                    <div class="flex gap-4">
                        <button type="button" onclick="closeEditForm()" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold uppercase text-[10px] transition-all">Fermer</button>
                        <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-[10px] transition-all shadow-lg shadow-emerald-500/20">
                            Enregistrer
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] p-6 md:p-8">
                            <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">Titre de l'article</label>
                            <input type="text" name="title" id="edit_title" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white text-lg focus:border-blue-500 outline-none mb-6">

                            <label class="block text-[10px] font-bold text-white/40 uppercase mb-2">Contenu</label>
                            <div id="edit-editor-container" class="bg-white/5 border border-white/10 rounded-xl text-white min-h-[500px]"></div>
                            <input type="hidden" name="description" id="edit_description_input">
                        </div>
                    </div>

                    <div class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] p-6 h-fit">
                        <label class="block text-[10px] font-bold text-white/40 uppercase mb-4">Image de couverture actuelle</label>
                        <div id="edit-preview-container" class="mb-4 relative rounded-2xl overflow-hidden border border-white/10">
                            <img id="edit-image-preview" src="#" class="w-full h-48 object-cover hidden">

                            {{-- Bloc "Aucun Visuel" (affiché si src est vide) --}}
                            <div id="edit-no-image" class="flex flex-col items-center justify-center p-8">
                                <svg class="w-12 h-12 text-blue-500/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[10px] font-bold text-blue-500/40 uppercase tracking-widest text-center">Aucun visuel actuel</span>
                            </div>
                        </div>
                        <label for="edit_main_picture" class="flex flex-col items-center justify-center border-2 border-dashed border-white/10 rounded-2xl p-8 cursor-pointer hover:border-blue-500/50 transition-all bg-white/5">
                            <span class="text-white/40 text-xs font-black uppercase tracking-tighter">Remplacer l'image</span>
                            <input type="file" name="main_picture" id="edit_main_picture" class="hidden" accept="image/*" onchange="previewEditFile()">
                        </label>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>

    {{-- GRILLE D'ARTICLES --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
            <div class="bg-[#0d0d0f] border border-white/5 rounded-[2rem] overflow-hidden group hover:border-blue-500/30 transition-all">
                {{-- Image avec gestion de l'absence de photo --}}
                <div class="h-48 overflow-hidden relative">
                    @if($article->path_location)
                        <img src="{{ asset($article->path_location) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        {{-- Design de remplacement si pas d'image --}}
                        <div class="w-full h-full bg-gradient-to-br from-blue-900/40 to-[#0d0d0f] flex flex-col items-center justify-center border-b border-white/5">
                            <svg class="w-12 h-12 text-blue-500/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[10px] font-bold text-blue-500/40 uppercase tracking-widest">Aucun visuel</span>
                        </div>
                    @endif
                    
                    {{-- Badge de date --}}
                    <div class="absolute top-4 right-4 px-3 py-1 bg-black/60 backdrop-blur-md rounded-full border border-white/10 text-[10px] text-white/70">
                        {{ \Carbon\Carbon::parse($article->date_creation)->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-2 line-clamp-1 uppercase">{{ $article->title }}</h3>
                    
                    {{-- Début de description (nettoyage du HTML pour l'aperçu) --}}
                    <div class="text-white/40 text-sm mb-6 line-clamp-2">
                        {{ Str::limit(html_entity_decode(strip_tags($article->description)), 100) }}
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">{{ $article->author_name ?? 'Admin' }}</span>
                        
                        <div class="flex gap-2">

                            {{-- Bouton Newsletter (Bleu) --}}
                            <form action="{{ route('admin.blog.newsletter', $article->id_blog) }}" method="POST" 
                                onsubmit="return confirm('Confirmez-vous l\'envoi de cette analyse stratégique aux clients (Role 1) ?')" 
                                class="inline">
                                @csrf
                                <button type="submit" class="p-2 bg-blue-500/10 hover:bg-blue-600/30 rounded-lg text-blue-400 hover:text-blue-200 transition-all group" title="Envoyer aux abonnés">
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19l6.75-12.75L21 3l-8.25 18L3 19zm0 0l9-9"/>
                                    </svg>
                                </button>
                            </form>
                            {{-- Bouton Modifier --}}
                            <button type="button" 
                                onclick="openEditForm({{ json_encode($article) }}, '{{ route('admin.blog.update', $article->id_blog) }}')" 
                                class="p-2 bg-white/5 hover:bg-blue-500/20 rounded-lg text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            {{-- Bouton Supprimer --}}
                            <form action="{{ route('admin.blog.destroy', $article->id_blog) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-white/5 hover:bg-red-500/20 rounded-lg text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
</div>


<script>
    // Toggle pour le formulaire de création
    function toggleCreateForm() {
        const container = document.getElementById('create-article-container');
        const editContainer = document.getElementById('edit-article-container'); // On récupère l'autre
        const icon = document.getElementById('toggle-icon');
        const text = document.getElementById('toggle-text');

        if (container.classList.contains('hidden')) {
            // On ferme le formulaire d'édition s'il est ouvert
            if(!editContainer.classList.contains('hidden')) closeEditForm();
            
            container.classList.remove('hidden');
            setTimeout(() => container.classList.remove('opacity-0', 'translate-y-4'), 10);
            text.innerText = "Fermer l'éditeur";
            if(icon) icon.style.transform = "rotate(45deg)";
        } else {
            container.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => container.classList.add('hidden'), 500);
            text.innerText = "Nouvel Article";
            if(icon) icon.style.transform = "rotate(0deg)";
        }
    }

    function openEditForm(article, updateUrl) {
        const container = document.getElementById('edit-article-container');
        const content = document.getElementById('edit-modal-content');
        const form = document.getElementById('edit-blog-form');

        form.action = updateUrl;
        document.getElementById('edit_title').value = article.title;
        
        
        const previewImg = document.getElementById('edit-image-preview');
        const noImageBlock = document.getElementById('edit-no-image');

        // Correction ici : On vérifie si path_location existe et n'est pas vide
        if (article.path_location && article.path_location !== 'null') {
            // On ajoute le slash devant seulement si c'est un chemin relatif
            previewImg.src = article.path_location.startsWith('http') ? article.path_location : '/' + article.path_location;
            previewImg.classList.remove('hidden');
            noImageBlock.classList.add('hidden');
        } else {
            previewImg.src = "";
            previewImg.classList.add('hidden');
            noImageBlock.classList.remove('hidden');
        }

        window.dispatchEvent(new CustomEvent('fill-edit-editor', { 
            detail: { content: article.description } 
        }));

        container.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
        setTimeout(() => {
            container.classList.remove('opacity-0');
            content.classList.remove('translate-y-10');
        }, 10);
    }

    function closeEditForm() {
        const container = document.getElementById('edit-article-container');
        container.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => container.classList.add('hidden'), 500);
    }

    function previewFile() {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        const file = document.getElementById('main_picture').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => { 
                preview.src = reader.result; 
                container.classList.remove('hidden'); 
            }
            reader.readAsDataURL(file);
        }
    }

    function previewEditFile() {
        const preview = document.getElementById('edit-image-preview');
        const noImageBlock = document.getElementById('edit-no-image');
        const file = document.getElementById('edit_main_picture').files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onloadend = function () {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                noImageBlock.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('main_picture').value = "";
        document.getElementById('preview-container').classList.add('hidden');
    }

    @if($errors->any())
        window.addEventListener('load', () => toggleCreateForm());
    @endif
</script>
    

    {{-- CHARGEMENT DES SCRIPTS --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.21/mammoth.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

    {{-- Ton nouveau fichier JS --}}
    @vite(['resources/js/blog-editor.js'])

    <style>
        /* Garde le CSS ici car il contient des variables Blade ou spécifiques au thème noir */
        .ql-toolbar.ql-snow { border-color: rgba(255,255,255,0.1); background: #1a1a1c; border-radius: 12px 12px 0 0; }
        .ql-container.ql-snow { border-color: rgba(255,255,255,0.1); border-radius: 0 0 12px 12px; height: 400px; }
        .ql-editor { font-size: 16px; color: white; }

        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        .animate-bounce-short {
            animation: bounce-short 0.5s ease-out;
        }
    </style>

@endsection