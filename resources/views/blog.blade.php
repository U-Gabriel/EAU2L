@extends('layouts.app')

@section('title', 'Actualités & Stratégies')

@php
    $theme = DB::table('page_blocks')->where('id_page', 6)->where('type', 'like', 'color_%')->get()->pluck('content', 'type');
    // Fallbacks pour compatibilité
    $primary = $theme['color_primary'] ?? '#3b82f6';
    $bg = $theme['color_bg_dark'] ?? '#020617';
    $card = $theme['color_bg_card'] ?? '#1a1a1c';
    $text = $theme['color_text_light'] ?? '#ffffff';
    $gray = $theme['color_text_gray'] ?? '#94a3b8';
    $border = $theme['color_border'] ?? 'rgba(255,255,255,0.1)';
@endphp

<style>
    :root {
        --primary: {{ $primary }};
        --bg: {{ $bg }};
        --card: {{ $card }};
        --text: {{ $text }};
        --gray: {{ $gray }};
        --border: {{ $border }};
    }

    /* Application globale */
    body, .main-content, section { background-color: var(--bg) !important; color: var(--text); }
    
    /* Cartes et Inputs (Contact & Blog) */
    .card, .premium-card, .contact-form, .bg-white\/5, .discovery-card { 
        background-color: var(--card) !important; 
        border: 1px solid var(--border) !important; 
    }

    /* Typographie */
    h1, h2, h3, .text-white { color: var(--text) !important; }
    p, .text-gray-400, .author-role { color: var(--gray) !important; }

    /* Actions */
    .btn-primary, .bg-blue-600, .btn-discovery-premium:hover { 
        background-color: var(--primary) !important; 
        border-color: var(--primary) !important;
        color: white !important;
    }
    .text-blue-500, .step-number, .quote-mark { color: var(--primary) !important; }

    /* Bordures de séparation */
    hr, .border-b, .border-white\/10 { border-color: var(--border) !important; }
</style>

@section('content')
        <div class="insights-corporate-wrapper">
            <div class="container">
                <div class="editorial-header d-flex justify-content-between align-items-end">
            <div>
                <h1 class="fw-black text-white">Actualités<span class="text-blue-500">.</span></h1>
                <p class="insight-subtitle">Analyses et perspectives stratégiques pour dirigeants.</p>
            </div>
            <button class="btn-newsletter-trigger no-print" onclick="toggleNewsletter()">
                <i class="bi bi-envelope-at me-2"></i> Suivre les actualités
            </button>
        </div>

        {{-- Card Newsletter --}}
        <div id="newsletter-card" class="newsletter-card-hidden no-print">
            <div class="newsletter-container d-flex flex-column align-items-start"> {{-- Changement ici : colonne --}}
                
                <div id="newsletter-content-wrapper" class="w-100">
                    <div class="d-flex justify-content-between align-items-start w-100">
                        <div class="newsletter-text">
                            <h3 class="text-white fw-bold mb-1">Veille Stratégique</h3>
                            <p id="newsletter-desc-text" class="newsletter-description">Anticipez les mutations de votre marché. Recevez nos analyses exclusives directement par email.</p>
                        </div>

                        <form id="newsletter-form" class="newsletter-form-ajax">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="mail" id="newsletter_email" placeholder="votre@email.com" required class="newsletter-input">
                                <button type="submit" class="newsletter-submit">Valider</button>
                            </div>
                            <small id="newsletter-error" class="text-danger d-block mt-2" style="display:none;"></small>
                        </form>
                    </div>
                </div>

                {{-- Message de succès : il apparaîtra ici, en dessous, une fois le formulaire masqué --}}
                <div id="newsletter-success-msg" class="newsletter-success-container" style="display:none;">
                    <div class="d-flex align-items-center mt-2">
                        <i class="bi bi-check-circle-fill text-green-500 me-3" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong class="text-white d-block">Les articles vous seront bien envoyés !</strong>
                            <span class="text-green-400 small">Merci pour votre confiance. Bienvenue dans notre cercle d'expertise.</span>
                        </div>
                    </div>
                </div>

                <button class="btn-close-newsletter" onclick="toggleNewsletter()">×</button>
            </div>
        </div>

        <div class="insights-accordion">
            @forelse($posts as $post)
                @php $firstPic = $post->pictures->first(); @endphp
                
                <div class="insight-item" id="insight-{{ $post->id_blog }}">
                    {{-- En-tête cliquable --}}
                    <div class="insight-trigger" onclick="toggleInsight({{ $post->id_blog }})">
                        <div class="insight-row-compact">
                            <div class="insight-visual-mini">
                                @if($firstPic)
                                    <img src="{{ asset($firstPic->path_location) }}" alt="">
                                @else
                                    <div class="insight-placeholder"></div>
                                @endif
                            </div>

                            <div class="insight-main-info">
                                <div class="insight-top-meta">
                                    <span class="insight-category">Expertise</span>
                                    <span class="insight-reading-time">
                                        @php
                                            $wordCount = str_word_count(strip_tags($post->description));
                                            $minutes = ceil($wordCount / 200); // 200 mots par minute est la moyenne humaine
                                        @endphp
                                        {{ $minutes }} MIN DE LECTURE
                                    </span>
                                </div>
                                <h2 class="insight-headline">{{ $post->title }}</h2>
                                {{-- Les premières lignes pour donner envie --}}
                                <p class="insight-teaser">{{ Str::limit(strip_tags($post->description), 120) }}</p>
                            </div>

                            <div class="insight-action">
                                <span class="action-label">Lire</span>
                                <i class="bi bi-plus-lg toggle-icon"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Contenu déroulant (Épuré) --}}
                    <div class="insight-content-collapse" id="content-{{ $post->id_blog }}">
                        <div class="content-inner-clean">
                            {{-- LOGO POUR L'IMPRESSION UNIQUEMENT --}}
                           
                            <div class="full-article-body">
                                {!! $post->description !!}
                            </div>
                            <div class="content-footer-divider">
                                <span>Publié le {{ \Carbon\Carbon::parse($post->date_creation)->translatedFormat('d F Y') }}</span>
                                <button class="btn-minimal no-print" onclick="window.print()">
                                    <i class="bi bi-printer me-2"></i> Imprimer l'analyse
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-white/20 py-10">Aucune analyse disponible pour le moment.</p>
            @endforelse
            <div class="mt-5 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

<style>
/* Base Wrapper */
.insights-corporate-wrapper { background: #020617; min-height: 100vh; padding: 120px 0 80px; }
.fw-black { font-weight: 900; letter-spacing: -2px; font-size: 3.5rem; }
.editorial-header { margin-bottom: 60px; border-left: 4px solid #3b82f6; padding-left: 30px; }

/* Accordion Styling */
.insight-item { border-top: 1px solid rgba(255,255,255,0.08); transition: all 0.3s; }
.insight-item:last-child { border-bottom: 1px solid rgba(255,255,255,0.08); }
.insight-item.active { background: rgba(255,255,255,0.02); }

.insight-trigger { padding: 35px 0; cursor: pointer; }

.insight-row-compact {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 40px;
    align-items: center;
}

/* Éléments de l'en-tête */
.insight-visual-mini { height: 70px; border-radius: 4px; overflow: hidden; }
.insight-visual-mini img { width: 100%; height: 100%; object-fit: cover; opacity: 0.5; margin-left: 1em}

.insight-category { color: #3b82f6; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
.insight-reading-time { color: rgba(255,255,255,0.4); font-size: 0.75rem; font-weight: 600; margin-left: 20px; }

.insight-headline { color: #fff; font-size: 1.7rem; font-weight: 700; margin: 8px 0; transition: color 0.3s; }
.insight-teaser { color: rgba(255,255,255,0.4); font-size: 0.95rem; line-height: 1.5; margin: 0; max-width: 800px; }

.insight-item:hover .insight-headline { color: #3b82f6; }

/* Animation du bouton */
.insight-action { color: #fff; display: flex; align-items: center; gap: 15px; }
.action-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; opacity: 0.4; }
.toggle-icon { font-size: 1.2rem; transition: transform 0.4s ease; }
.insight-item.active .toggle-icon { transform: rotate(45deg); color: #3b82f6; }

/* Contenu de l'article (Sans image à droite) */
.insight-content-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.8s cubic-bezier(0, 1, 0, 1);
}

.insight-item.active .insight-content-collapse {
    max-height: 5000px; /* Assez grand pour tout le texte */
    transition: max-height 1.2s ease-in;
}

.content-inner-clean {
    padding: 0 0 60px 140px; /* Aligné avec le texte de l'en-tête */
    max-width: 950px; /* Largeur de lecture optimale pour le confort visuel */
}

.full-article-body {
    color: rgba(255,255,255,0.7);
    font-size: 1.15rem;
    line-height: 1.9;
    letter-spacing: 0.01em;
}
.full-article-body img {
    display: block;
    margin: 40px auto; /* Centre l'image et ajoute de l'espace en haut et en bas */
    max-width: 90%;    /* Évite que l'image ne prenne toute la largeur pour plus d'élégance */
    border-radius: 8px; /* Un léger arrondi pour le côté premium */
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); /* Donne du relief sur le fond sombre */
}

.full-article-body p { margin-bottom: 1.5rem; }

/* Footer de l'article ouvert */
.content-footer-divider {
    margin-top: 40px;
    padding-top: 25px;
    border-top: 1px solid rgba(255,255,255,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: rgba(255,255,255,0.3);
    font-size: 0.85rem;
}

.btn-minimal {
    background: transparent;
    border: none;
    color: #3b82f6;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    cursor: pointer;
    transition: 0.3s;
}
.btn-minimal:hover { color: #fff; }

/* Mobile */
@media (max-width: 991px) {
    .insight-row-compact { grid-template-columns: 1fr auto; }
    .insight-visual-mini { display: none; }
    .content-inner-clean { padding: 0 20px 40px 20px; }
}

@media print {
    /* 1. Nettoyage total */
    nav, footer, .editorial-header, .no-print, .insight-trigger, .insight-visual-mini, .action-label {
        display: none !important;
    }

    @page {
        size: A4;
        margin: 0cm 0cm
    }

    /* 2. Style du document - Noir sur Blanc */
    body {
        background: white !important;
        color: #000 !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: 'Times New Roman', serif;
    }

    .insight-item.active {
        display: block !important;
        border: none !important;
        background: white !important;
    }

    /* 3. En-tête Armature Business */
    .content-inner-clean::before {
        content: "ARMATURE BUSINESS — NOTE D'EXPERTISE" !important;
        display: block;
        text-align: center;
        font-size: 10pt;
        font-family: sans-serif;
        letter-spacing: 3px;
        color: #000;
        border-bottom: 1.5pt solid #000;
        padding-bottom: 10px;
        margin-bottom: 40px;
    }

    /* 4. Titre de l'article */
    .insight-headline {
        color: #000 !important;
        font-size: 24pt !important;
        font-weight: bold !important;
        margin-bottom: 5pt !important;
        text-align: left;
    }

    /* 5. Sous-titre et Date */
    .full-article-body::before {
        content: "ANALYSE STRATÉGIQUE | PUBLIÉ LE {{ \Carbon\Carbon::parse($post->date_creation)->translatedFormat('d F Y') }}";
        display: block;
        font-size: 10pt;
        color: #000 !important;
        margin-bottom: 30px;
        font-family: sans-serif;
        font-weight: bold;
    }

    /* 6. Corps de texte - Lisibilité maximale */
    .full-article-body {
        color: #000000 !important;
        font-size: 12pt !important;
        line-height: 1.6 !important;
        text-align: justify;
    }

    /* 7. Footer du rapport */
    .content-footer-divider {
        margin-top: 50px;
        border-top: 1pt solid #000 !important;
        padding-top: 10px;
        display: flex;
        justify-content: space-between;
        font-size: 9pt !important;
        color: #000 !important;
        font-family: sans-serif;
    }

    .content-footer-divider::after {
        content: "© Armature Business - Tous droits réservés";
    }

     /* Affiche le logo uniquement à l'impression */
    .print-header-logo {
        display: block !important;
        text-align: center;
        margin-bottom: 20px;
    }

    .print-header-logo img {
        width: 180px; /* Ajuste la taille selon tes besoins */
        height: auto;
        filter: brightness(0); /* Force le logo en noir si besoin, ou enlève cette ligne pour garder les couleurs */
    }

    /* 1. On cache les ailes grises et les textes parasites du navigateur */
    /* Ces sélecteurs ciblent souvent les éléments de header générés */
    header, .navbar, .wings-icon { 
        display: none !important; 
    }

    /* 2. On affiche et on centre ton nouveau logo */
    .print-header-branding {
        display: block !important;
        text-align: center;
        width: 100%;
        margin-bottom: 20px;
    }

    .print-header-branding img {
        height: 80px; /* Taille élégante pour un en-tête */
        width: auto;
        display: inline-block;
    }

    /* 3. On ajuste le titre "NOTE D'EXPERTISE" pour qu'il soit juste en dessous */
    .content-inner-clean::before {
        content: "NOTE D'EXPERTISE STRATÉGIQUE" !important;
        border-top: none !important; /* On enlève la bordure si elle gène le logo */
        margin-top: 10px;
    }
}


/* Cache le logo sur le site web */
.print-header-logo {
    display: none;
}

/* Cache le header d'impression sur le site web */
.print-header-branding {
    display: none;
}

/* Style pour le sous-titre plus visible */
.insight-subtitle {
    color: rgba(255, 255, 255, 0.9); /* Blanc presque pur */
    font-size: 1rem;
    font-weight: 300;
    letter-spacing: 0.5px;
    margin-top: 10px;
    max-width: 600px;
    line-height: 1.6;
}

.content-inner-clean {
    padding: 20px 40px 60px 60px; 
    max-width: 1000px;
    margin: 0;
}

/* Bouton Trigger Newsletter */
.btn-newsletter-trigger {
    background: transparent;
    border: 1px solid #3b82f6;
    color: #3b82f6;
    padding: 10px 25px;
    border-radius: 4px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
    transition: all 0.3s;
    margin-bottom: 15px;
}
.btn-newsletter-trigger:hover {
    background: #3b82f6;
    color: #fff;
}

/* Card Newsletter Overlay */
.newsletter-card-hidden {
    display: none;
    margin-bottom: 40px;
    animation: slideDown 0.4s ease-out;
}

.newsletter-container {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(59, 130, 246, 0.3);
    padding: 30px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    gap: 30px;
}

.newsletter-input {
    background: #020617;
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    padding: 12px 20px;
    border-radius: 4px;
    width: 300px;
}

.newsletter-submit {
    background: #3b82f6;
    border: none;
    color: #fff;
    padding: 12px 25px;
    border-radius: 4px;
    font-weight: 700;
    margin-left: 10px;
}

.btn-close-newsletter {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    color: rgba(255,255,255,0.3);
    font-size: 1.5rem;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .newsletter-container { flex-direction: column; text-align: center; }
    .newsletter-input { width: 100%; }
}

.newsletter-container {
    background: linear-gradient(90deg, rgba(255,255,255,0.03) 0%, rgba(59,130,246,0.05) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 25px 40px;
    border-radius: 12px;
    display: flex;
    position: relative;
    margin-bottom: 30px;
}

.newsletter-input {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 10px 20px;
    border-radius: 4px 0 0 4px; /* Arrondi à gauche uniquement */
    width: 280px;
    outline: none;
}

.newsletter-submit {
    background: #3b82f6;
    border: none;
    color: #fff;
    padding: 10px 25px;
    border-radius: 0 4px 4px 0; /* Arrondi à droite uniquement */
    font-weight: 600;
    transition: 0.3s;
}

.newsletter-success-overlay {
    display: flex;
    align-items: center;
    color: #fff;
    width: 100%;
    animation: fadeIn 0.4s ease;
}

.text-green-500 { color: #10b981 !important; }
.text-green-400 { color: #34d399 !important; }

.newsletter-success-container {
    width: 100%;
    animation: fadeIn 0.4s ease-out;
    border-top: 1px solid rgba(16, 185, 129, 0.2); /* Petite ligne de séparation verte subtile */
    padding-top: 15px;
    margin-top: 10px;
}

/* On s'assure que la box s'adapte au contenu vertical */
.newsletter-container {
    min-height: auto; 
    padding: 25px 40px;
}

/* ============================================================
   SPÉCIAL PORTABLE (MOBILE-ONLY)
   ============================================================ */
@media (max-width: 768px) {
    /* Ajustement Header */
    .insights-corporate-wrapper { padding: 80px 0 40px; }
    .fw-black { font-size: 2.2rem; letter-spacing: -1px; }
    .editorial-header { 
        flex-direction: column !important; 
        align-items: flex-start !important; 
        padding-left: 15px;
        gap: 20px;
    }
    .insight-subtitle { font-size: 0.9rem; }

    /* Newsletter Mobile - Mode Vertical Pro */
    .newsletter-container { 
        flex-direction: column !important; 
        padding: 30px 20px; 
        text-align: center;
        gap: 20px;
    }
    .newsletter-form-ajax, .newsletter-form-ajax .input-group { 
        width: 100%; 
        display: flex; 
        flex-direction: column; 
    }
    .newsletter-input { 
        width: 100%; 
        border-radius: 8px !important; 
        margin-bottom: 10px; 
        height: 50px;
    }
    .newsletter-submit { 
        width: 100%; 
        border-radius: 8px !important; 
        height: 50px;
        margin: 0;
    }

    /* Accordion Mobile - Plus d'espace pour le texte */
    .insight-trigger { padding: 25px 0; }
    .insight-row-compact { 
        grid-template-columns: 1fr auto; /* On vire l'image qui prend trop de place */
        gap: 15px;
    }
    .insight-visual-mini { display: none !important; }
    
    .insight-headline { font-size: 1.25rem; line-height: 1.4; }
    .insight-teaser { font-size: 0.85rem; opacity: 0.6; }
    
    .insight-action .action-label { display: none; } /* On cache "Lire", l'icône + suffit */

    /* Contenu Article Mobile */
    .content-inner-clean { 
        padding: 10px 0 30px 0; /* On retire le padding de 140px qui écrase le texte */
    }
    .full-article-body { 
        font-size: 1.05rem; 
        line-height: 1.7; 
    }
    .full-article-body img {
        max-width: 100%;
        margin: 20px 0;
    }
    
    .content-footer-divider {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    /* On force la carte newsletter à s'empiler proprement */
    .newsletter-container {
        flex-direction: column !important;
        align-items: stretch !important;
        padding: 40px 20px !important;
        gap: 20px !important;
    }

    /* Le wrapper qui contient le titre et le form */
    #newsletter-content-wrapper > .d-flex {
        flex-direction: column !important;
        gap: 20px !important;
    }

    /* On s'assure que le texte prend toute la place */
    .newsletter-text {
        width: 100% !important;
        text-align: center !important;
    }

    /* LE FORMULAIRE : On casse l'alignement horizontal */
    .newsletter-form-ajax {
        width: 100% !important;
    }

    .newsletter-form-ajax .input-group {
        display: flex !important;
        flex-direction: column !important; /* Force l'un sous l'autre */
        width: 100% !important;
        gap: 15px !important; /* Espace entre l'email et le bouton */
    }

    /* L'INPUT : On lui donne sa propre ligne */
    .newsletter-input {
        width: 100% !important;
        height: 55px !important;
        border-radius: 8px !important; /* Arrondi sur tous les coins */
        margin: 0 !important;
        padding: 0 15px !important;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        font-size: 16px !important;
    }

    /* LE BOUTON : On lui donne sa propre ligne */
    .newsletter-submit {
        width: 100% !important;
        height: 55px !important;
        margin: 0 !important;
        border-radius: 8px !important; /* Arrondi sur tous les coins */
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        position: static !important; /* Au cas où un absolute traînerait */
    }

    /* On gère l'espacement global du wrapper */
    .insights-corporate-wrapper {
        padding-top: 60px !important;
    }

    /* Correction du titre principal */
    .fw-black {
        font-size: 2rem !important;
        text-align: left;
    }

    .insight-content-collapse {
        max-height: 0;
        overflow: hidden;
        /* transition fluide pour l'ouverture ET la fermeture */
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
    }
}


</style>

<script>
function toggleInsight(id) {
    const item = document.getElementById('insight-' + id);
    const wasActive = item.classList.contains('active');
    const allItems = document.querySelectorAll('.insight-item');
    
    // 1. Si on clique sur un article déjà ouvert, on le ferme simplement
    if (wasActive) {
        item.classList.remove('active');
        return;
    }

    // 2. On ferme TOUS les articles d'abord
    allItems.forEach(el => el.classList.remove('active'));
    
    // 3. On ouvre le nouveau
    item.classList.add('active');

    // 4. On attend que la fermeture des autres ait commencé à stabiliser la page
    // 250ms est le "sweet spot" pour que le navigateur recalcule bien le centre
    setTimeout(() => {
        item.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }, 250); 
}

function toggleNewsletter() {
    const card = document.getElementById('newsletter-card');
    card.style.display = (card.style.display === 'none' || card.style.display === '') ? 'block' : 'none';
}

document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('newsletter_email').value;
    const errorSpan = document.getElementById('newsletter-error');
    const wrapper = document.getElementById('newsletter-content-wrapper');
    const successMsg = document.getElementById('newsletter-success-msg');

    fetch("{{ route('newsletter.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mail: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // On cache uniquement le formulaire et la description grise
            document.getElementById('newsletter-form').style.display = 'none';
            document.getElementById('newsletter-desc-text').style.display = 'none';
            
            // On affiche le bloc de succès vert en dessous
            document.getElementById('newsletter-success-msg').style.display = 'block';
        } else {
            errorSpan.innerText = data.message || "Une erreur est survenue.";
            errorSpan.style.display = 'block';
        }
    })
    .catch(error => {
        errorSpan.innerText = "Ce mail est probablement déjà inscrit.";
        errorSpan.style.display = 'block';
    });
});
</script>
@endsection