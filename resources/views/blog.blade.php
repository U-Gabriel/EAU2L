@extends('layouts.app')

@section('title', 'Actualités & Stratégies')
@section('meta_description', 'Suivez nos décryptages, analyses exclusives et leviers de croissance pour dirigeants de TPE/PME. Optimisez la gestion financière de votre entreprise.')

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
            <div class="newsletter-container d-flex flex-column align-items-start">
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

                <div id="newsletter-success-msg" class="newsletter-success-container" style="display:none;">
                    <div class="d-flex align-items-center mt-2">
                        <i class="bi bi-check-circle-fill text-green-500 me-3" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong class="text-white d-block">Les articles vous seront bien envoyés !</strong>
                            <span class="text-green-400 small">Merci pour votre confiance. Bienvenue dans notre cercle d'expertise.</span>
                        </div>
                    </div>
                </div>

                <!-- <button class="btn-close-newsletter" onclick="toggleNewsletter()">×</button> -->
            </div>
        </div>

        <div class="insights-accordion">
            @forelse($posts as $post)
                @php 
                    $firstPic = $post->pictures->first(); 
                    $postSlug = Str::slug($post->title);
                    $postUrl = url('/insights/' . $postSlug);
                @endphp
                
                <div class="insight-item" id="insight-{{ $post->id_blog }}">
                    {{-- Balise 'a' ajoutée pour Google SEO, mais le clic JS est conservé pour l'expérience fluide --}}
                    <a href="{{ $postUrl }}" class="insight-trigger-link" style="text-decoration: none; display: block;" onclick="event.preventDefault(); toggleInsight({{ $post->id_blog }});">
                        <div class="insight-trigger">
                            <div class="insight-row-compact">
                                <div class="insight-visual-mini">
                                    @if($firstPic)
                                        <img src="{{ asset($firstPic->path_location) }}" alt="{{ $post->title }}">
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
                                                $minutes = ceil($wordCount / 200);
                                            @endphp
                                            {{ $minutes }} MIN DE LECTURE
                                        </span>
                                    </div>
                                    <h2 class="insight-headline">{{ $post->title }}</h2>
                                    <p class="insight-teaser">{{ Str::limit(strip_tags($post->description), 120) }}</p>
                                </div>

                                <div class="insight-action">
                                    <span class="action-label">Lire</span>
                                    <i class="bi bi-plus-lg toggle-icon"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Contenu déroulant --}}
                    <div class="insight-content-collapse" id="content-{{ $post->id_blog }}">
                        <div class="content-inner-clean">
                            <div class="full-article-body">
                                {!! $post->description !!}
                            </div>
                            <div class="content-footer-divider">
                                <span>Publié le {{ \Carbon\Carbon::parse($post->date_creation)->translatedFormat('d F Y') }}</span>
                                <div class="d-flex gap-3">
                                    <button class="btn-minimal no-print" onclick="window.print()">
                                        <i class="bi bi-printer me-2"></i> Imprimer l'analyse
                                    </button>
                                </div>
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
.insights-corporate-wrapper { 
    background: #020617; 
    min-height: 100vh; 
    padding: 120px 0 80px; 
    display: flow-root; /* Crée un nouveau contexte de formatage pour englober toutes les marges de tes articles */
    clear: both; 
}

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

/* Contenu de l'article */
.insight-content-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.8s cubic-bezier(0, 1, 0, 1);
}

.insight-item.active .insight-content-collapse {
    max-height: none !important; /* Permet au texte long de s'afficher intégralement */
    overflow: visible !important; /* Empêche le masquage ou la coupure brute en bas */
}

.content-inner-clean {
    padding: 0 0 60px 140px; 
    max-width: 950px; 
}

.full-article-body {
    color: rgba(255,255,255,0.7);
    font-size: 1.15rem;
    line-height: 1.9;
    letter-spacing: 0.01em;
}
.full-article-body img {
    display: block;
    margin: 40px auto; 
    max-width: 90%;    
    border-radius: 8px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
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
    nav, footer, .editorial-header, .no-print, .insight-trigger, .insight-visual-mini, .action-label {
        display: none !important;
    }

    @page {
        size: A4;
        margin: 0cm 0cm
    }

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

    .insight-headline {
        color: #000 !important;
        font-size: 24pt !important;
        font-weight: bold !important;
        margin-bottom: 5pt !important;
        text-align: left;
    }

    .full-article-body {
        color: #000000 !important;
        font-size: 12pt !important;
        line-height: 1.6 !important;
        text-align: justify;
    }

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

    .print-header-logo {
        display: block !important;
        text-align: center;
        margin-bottom: 20px;
    }

    .print-header-logo img {
        width: 180px;
        height: auto;
        filter: brightness(0);
    }

    header, .navbar, .wings-icon { 
        display: none !important; 
    }

    .print-header-branding {
        display: block !important;
        text-align: center;
        width: 100%;
        margin-bottom: 20px;
    }

    .print-header-branding img {
        height: 80px;
        width: auto;
        display: inline-block;
    }

    .content-inner-clean::before {
        content: "NOTE D'EXPERTISE STRATÉGIQUE" !important;
        border-top: none !important;
        margin-top: 10px;
    }
}

.print-header-logo, .print-header-branding { display: none; }

.insight-subtitle {
    color: rgba(255, 255, 255, 0.9);
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
.btn-newsletter-trigger:hover { background: #3b82f6; color: #fff; }

.newsletter-card-hidden { display: none; margin-bottom: 40px; animation: slideDown 0.4s ease-out; }

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
    border-radius: 4px 0 0 4px;
    width: 280px;
    outline: none;
}

.newsletter-submit {
    background: #3b82f6;
    border: none;
    color: #fff;
    padding: 10px 25px;
    border-radius: 0 4px 4px 0;
    font-weight: 600;
    transition: 0.3s;
}

.text-green-500 { color: #10b981 !important; }
.text-green-400 { color: #34d399 !important; }

.newsletter-success-container {
    width: 100%;
    animation: fadeIn 0.4s ease-out;
    border-top: 1px solid rgba(16, 185, 129, 0.2);
    padding-top: 15px;
    margin-top: 10px;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .insights-corporate-wrapper { padding: 60px 0 40px !important; }
    .fw-black { font-size: 2rem !important; text-align: left; }
    
    .editorial-header { 
        flex-direction: column !important; 
        align-items: flex-start !important; 
        padding-left: 15px;
        gap: 20px;
    }
    .insight-subtitle { font-size: 0.9rem; }

    .newsletter-container { 
        flex-direction: column !important; 
        align-items: stretch !important;
        padding: 40px 20px !important; 
        text-align: center;
        gap: 20px !important;
    }
    #newsletter-content-wrapper > .d-flex { flex-direction: column !important; gap: 20px !important; }
    .newsletter-text { width: 100% !important; text-align: center !important; }
    
    .newsletter-form-ajax { width: 100% !important; }
    .newsletter-form-ajax .input-group { display: flex !important; flex-direction: column !important; width: 100% !important; gap: 15px !important; }
    
    .newsletter-input { 
        width: 100% !important; 
        border-radius: 8px !important; 
        height: 55px !important;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        font-size: 16px !important;
        margin: 0 !important;
    }
    .newsletter-submit { 
        width: 100% !important; 
        border-radius: 8px !important; 
        height: 55px !important;
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        margin: 0 !important;
    }

    .insight-trigger { padding: 25px 0; }
    .insight-row-compact { grid-template-columns: 1fr auto; gap: 15px; }
    .insight-visual-mini { display: none !important; }
    .insight-headline { font-size: 1.25rem; line-height: 1.4; }
    .insight-teaser { font-size: 0.85rem; opacity: 0.6; }
    .insight-action .action-label { display: none; }

    .content-inner-clean { padding: 10px 0 30px 0; }
    .full-article-body { font-size: 1.05rem; line-height: 1.7; }
    .full-article-body img { max-width: 100%; margin: 20px 0; }
    .content-footer-divider { flex-direction: column; gap: 15px; text-align: center; }
}
</style>

<script>
function toggleInsight(id) {
    const item = document.getElementById('insight-' + id);
    const wasActive = item.classList.contains('active');
    const allItems = document.querySelectorAll('.insight-item');
    
    if (wasActive) {
        item.classList.remove('active');
        return;
    }

    allItems.forEach(el => el.classList.remove('active'));
    item.classList.add('active');

    setTimeout(() => {
        item.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
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
            document.getElementById('newsletter-form').style.display = 'none';
            document.getElementById('newsletter-desc-text').style.display = 'none';
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