@extends('layouts.app')

@section('title', 'Expert en Trésorerie et Rentabilité TPE/PME')
@section('meta_description', 'Optimisez votre gestion financière et boostez la rentabilité de votre entreprise avec Armature Business. Accompagnement stratégique sur mesure pour dirigeants.')

@php
    $theme = DB::table('page_blocks')->where('id_page', 6)->where('type', 'like', 'color_%')->get()->pluck('content', 'type');
    // Fallbacks pour compatibilité
    $primary = $theme['color_primary'] ?? '#C5973B';
    $bg = $theme['color_bg_dark'] ?? '#0B131F';
    $card = $theme['color_bg_card'] ?? '#131F32';
    $text = $theme['color_text_light'] ?? '#ffffff';
    $gray = $theme['color_text_gray'] ?? '#94a3b8';
    $border = $theme['color_border'] ?? 'rgba(255,255,255,0.08)';

    // Préparation dynamique des données structurées
    $graphs = [
        [
            "@type" => "LocalBusiness",
            "@id" => url('/') . "#organization",
            "name" => "Armature Business",
            "url" => url('/'),
            "logo" => asset('images/logo_armature.png'),
            "image" => asset('images/logo_armature.png'),
            "description" => "Expert en Trésorerie et Rentabilité TPE/PME. Optimisez votre gestion financière et boostez la rentabilité de votre entreprise.",
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => "FR"
            ]
        ],
        [
            "@type" => "WebSite",
            "@id" => url('/') . "#website",
            "url" => url('/'),
            "name" => "Armature Business",
            "publisher" => [
                "@id" => url('/') . "#organization"
            ]
        ],
        [
            "@type" => "WebPage",
            "@id" => url()->current() . "#webpage",
            "url" => url()->current(),
            "name" => "Expert en Trésorerie et Rentabilité TPE/PME | Armature Business",
            "isPartOf" => [
                "@id" => url('/') . "#website"
            ],
            "description" => "Optimisez votre gestion financière et boostez la rentabilité de votre entreprise avec Armature Business. Accompagnement stratégique sur mesure."
        ]
    ];

    if (isset($faqs) && $faqs->count() > 0) {
        $faqItems = [];
        foreach ($faqs as $faq) {
            $fData = json_decode($faq->content);
            if (!empty($fData->title)) {
                $faqItems[] = [
                    "@type" => "Question",
                    "name" => strip_tags($fData->title),
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => strip_tags($fData->description ?? '')
                    ]
                ];
            }
        }
        if (!empty($faqItems)) {
            $graphs[] = [
                "@type" => "FAQPage",
                "@id" => url()->current() . "#faq",
                "mainEntity" => $faqItems
            ];
        }
    }

    if (isset($goals) && $goals->count() > 0) {
        $goalItems = [];
        foreach ($goals as $index => $goal) {
            $gData = json_decode($goal->content);
            $goalItems[] = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "name" => strip_tags($gData->title ?? 'Étape'),
                "description" => strip_tags($gData->description ?? '')
            ];
        }
        $graphs[] = [
            "@type" => "ItemList",
            "@id" => url()->current() . "#strategy",
            "name" => "Votre trajectoire financière",
            "description" => "Une approche structurée pour transformer votre gestion financière en levier de croissance.",
            "itemListElement" => $goalItems
        ];
    }

    if ($videoBlock) {
        $vData = json_decode($videoBlock->content);
        $graphs[] = [
            "@type" => "VideoObject",
            "@id" => url()->current() . "#video",
            "name" => $vData->title ?? "Présentation Armature Business",
            "description" => "Découvrez l'accompagnement stratégique en gestion de trésorerie et rentabilité pour TPE/PME.",
            "thumbnailUrl" => [
                asset($videoBlock->image_path)
            ],
            "uploadDate" => "2026-01-01T08:00:00+01:00",
            "contentUrl" => asset($videoBlock->video_path),
            "embedUrl" => url()->current()
        ];
    }
    $schemaJson = json_encode(["@context" => "https://schema.org", "@graph" => $graphs], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@push('scripts')
<script type="application/ld+json">
{!! $schemaJson !!}
</script>
@endpush

<style>
    :root {
        --primary: {{ $primary }};
        --bg: {{ $bg }};
        --card: {{ $card }};
        --text: {{ $text }};
        --gray: {{ $gray }};
        --border: {{ $border }};
    }

    /* Application globale Premium */
    body, .main-content { 
        background-color: var(--bg) !important; 
        color: var(--text); 
        font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    
    .bg-dark-premium {
        background-color: var(--bg) !important;
    }

    /* Typographie Haute Fidélité */
    h1, h2, h3, h4, .text-white { 
        color: var(--text) !important; 
        font-family: 'DM Serif Display', Georgia, serif;
    }
    p, .text-gray-400 { 
        color: var(--gray) !important; 
        font-family: 'DM Sans', sans-serif;
    }

    /* Titres adaptatifs */
    .section-title {
        font-size: clamp(2rem, 4vw, 3rem) !important;
        line-height: 1.25;
    }

    /* --- SECTION HERO (CORRIGÉE MOBILE-FIRST) --- */
    .hero {
        padding: 130px 0 80px !important; 
        background: linear-gradient(180deg, rgba(15,27,45,0.4) 0%, var(--bg) 100%) !important;
        position: relative;
        overflow: hidden !important;
    }

    .hero-content-box {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important; /* Centré par défaut sur mobile pour la structure */
        text-align: center !important;
        justify-content: center !important;
    }

    .hero-eyebrow {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        color: var(--primary) !important;
        font-family: 'DM Sans', sans-serif !important;
        font-size: clamp(0.68rem, 2.5vw, 0.8rem) !important; /* Adaptatif pour éviter les retours à la ligne */
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        margin-bottom: 24px !important;
        width: 100%;
        flex-wrap: wrap;
    }

    .hero-eyebrow::before {
        content: '' !important;
        width: 16px !important;
        height: 2px !important;
        background: var(--primary) !important;
        display: inline-block;
    }

    .hero-main-title { width: 100%; }
    .hero-main-title h1 {
        font-size: clamp(1.9rem, 5.5vw, 3.5rem) !important;
        line-height: 1.25 !important;
        margin-bottom: 24px !important;
        font-weight: 400 !important;
        text-align: center !important;
    }

    .hero-main-title h1 em {
        font-style: italic !important;
        color: var(--primary) !important;
    }

    .hero-description { width: 100%; }
    .hero-description p {
        font-size: clamp(0.95rem, 2.8vw, 1.15rem) !important;
        color: rgba(255, 255, 255, 0.7) !important;
        line-height: 1.6;
        text-align: center !important;
        margin-bottom: 0 !important;
    }

    .hero-actions {
        display: flex !important;
        flex-direction: column !important;
        gap: 12px !important;
        justify-content: center !important;
        margin-top: 36px !important;
        width: 100%;
        max-width: 420px; /* Évite l'étirement excessif des boutons sur mobile */
    }

    /* Boutons Design Institutionnel */
    .btn-primary-gold {
        background: var(--primary) !important;
        color: var(--bg) !important;
        font-family: 'DM Sans', sans-serif !important;
        font-weight: 700 !important;
        padding: 16px 32px !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        font-size: 1rem !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 20px rgba(197, 151, 59, 0.2);
        width: 100% !important;
    }

    .btn-primary-gold:hover {
        opacity: 0.95 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 24px rgba(197, 151, 59, 0.3);
        color: var(--bg) !important;
    }

    .btn-outline-white {
        border: 1.5px solid rgba(255, 255, 255, 0.2) !important;
        color: #FFFFFF !important;
        font-family: 'DM Sans', sans-serif !important;
        padding: 16px 32px !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        font-size: 1rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        background: transparent !important;
        width: 100% !important;
    }

    .btn-outline-white:hover {
        border-color: #FFFFFF !important;
        background: rgba(255, 255, 255, 0.05) !important;
        color: #FFFFFF !important;
    }

    /* --- SECTION VIDEO --- */
    .video-section {
        padding: 80px 0 !important;
        background: var(--bg) !important;
    }
    .video-container {
        max-width: 960px;
        position: relative;
    }
    .video-wrapper {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        background: #000;
    }
    .main-video {
        width: 100%;
        display: block;
        aspect-ratio: 16/9;
    }
    .video-caption {
        text-align: center;
        margin-top: 20px;
        font-size: 0.95rem;
        color: var(--gray);
        font-style: italic;
    }

    /* --- SECTION INSIGHTS DISCOVERY --- */
    .insights-discovery-section {
        padding: 40px 0 0 !important;
        background: var(--bg);
    }
    .discovery-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.02) 0%, rgba(255,255,255,0.05) 100%) !important;
        border: 1px solid var(--border) !important;
        border-radius: 24px;
        padding: 32px 24px;
        position: relative;
        overflow: hidden;
    }
    .discovery-grid {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 24px;
        position: relative;
        z-index: 2;
    }
    .discovery-text {
        text-align: center !important;
    }
    .discovery-badge {
        color: var(--primary);
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 2px;
        margin-bottom: 14px;
        display: block;
    }
    .discovery-title {
        color: #ffffff;
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 400;
        margin-bottom: 10px;
    }
    .btn-discovery-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #ffffff !important;
        color: var(--bg) !important;
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: transform 0.3s ease, background-color 0.3s ease;
        width: 100%;
    }
    .btn-discovery-premium:hover {
        background: var(--primary) !important;
        color: var(--bg) !important;
        transform: translateX(4px);
    }

    /* --- TRAJECTOIRE STRATÉGIQUE (GOALS) --- */
    .goals-section {
        padding: 80px 0 !important;
        background: var(--bg) !important;
    }
    .premium-goal-card {
        background: var(--card) !important;
        border: 1px solid var(--border) !important;
        padding: 30px 24px;
        border-radius: 20px;
        position: relative;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .premium-goal-card:hover {
        transform: translateY(-5px);
        border-color: rgba(197, 151, 59, 0.3) !important;
    }
    .step-number {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 2.8rem;
        color: rgba(197, 151, 59, 0.15) !important;
        position: absolute;
        top: 15px;
        right: 20px;
        line-height: 1;
    }
    .goal-icon-wrapper {
        width: 64px;
        height: 64px;
        background: rgba(197, 151, 59, 0.08);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 28px;
    }
    .goal-img {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }
    .engagement-banner {
        background: linear-gradient(135deg, rgba(197, 151, 59, 0.1) 0%, rgba(15, 27, 45, 0.2) 100%);
        border: 1px solid rgba(197, 151, 59, 0.2);
        border-radius: 24px;
        overflow: hidden;
    }
    .badge-premium {
        background: rgba(197, 151, 59, 0.15);
        color: var(--primary);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* --- TÉMOIGNAGES --- */
    .testimonials-section {
        padding: 100px 0 80px !important;
        background: var(--bg) !important;
    }
    .premium-testimonial-card {
        background: var(--card) !important;
        border: 1px solid var(--border) !important;
        padding: 32px 24px;
        border-radius: 24px;
        position: relative;
        margin: 0 10px;
    }
    .quote-mark {
        font-family: 'DM Serif Display', serif;
        font-size: 5rem;
        color: var(--primary) !important;
        position: absolute;
        top: 5px;
        left: 16px;
        opacity: 0.15;
    }
    .testimonial-text {
        font-size: 1.05rem;
        line-height: 1.7;
        color: rgba(255,255,255,0.85);
        margin-bottom: 30px;
        position: relative;
        z-index: 2;
    }
    .testimonial-profile {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .author-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
    }
    .author-name {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
    }
    .carousel-nav-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: var(--card);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .carousel-nav-btn:hover {
        background: var(--primary);
        color: var(--bg);
        border-color: var(--primary);
    }

    /* --- FAQ --- */
    .faq-section {
        padding: 80px 0 !important;
        background: var(--bg) !important;
    }
    .premium-faq-item {
        background: var(--card) !important;
        border: 1px solid var(--border) !important;
        border-radius: 14px;
        margin-bottom: 16px;
        overflow: hidden;
    }
    .faq-header {
        padding: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        font-size: 1rem;
        list-style: none;
        gap: 15px;
    }
    .faq-header::-webkit-details-marker { display: none; }
    .faq-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.3s;
    }
    details[open] .faq-icon {
        transform: rotate(45deg);
        background: var(--primary);
        color: var(--bg);
    }
    .faq-body {
        padding: 0 20px 20px;
        color: var(--gray);
        line-height: 1.6;
    }

    /* --- RE-DESIGN RESPONSIVE ÉCRANS LARGES (DESKTOP) --- */
    @media (min-width: 992px) {
        .hero { 
            padding: 180px 0 110px !important; 
        }
        .hero-content-box { 
            align-items: flex-start !important; 
            text-align: left !important;
        }
        .hero-eyebrow {
            justify-content: flex-start !important;
            font-size: 0.85rem !important;
            letter-spacing: 2.5px !important;
        }
        .hero-eyebrow::before {
            width: 30px !important;
        }
        .hero-main-title h1, .hero-description p { 
            text-align: left !important; 
        }
        .hero-main-title h1 {
            font-size: clamp(2.2rem, 5vw, 3.8rem) !important;
        }
        .hero-description p {
            font-size: clamp(1.05rem, 2.5vw, 1.2rem) !important;
        }
        .hero-actions { 
            flex-direction: row !important; 
            max-width: none !important;
            gap: 16px !important;
            margin-top: 40px !important;
        }
        .hero-actions .btn-primary-gold, 
        .hero-actions .btn-outline-white { 
            width: auto !important; 
        }
        
        .video-section, .goals-section {
            padding: 50px 0 !important;
        }

        /* On réduit le bas des témoignages et le haut de la FAQ pour resserrer la zone */
        .testimonials-section {
            padding: 100px 0 40px !important; /* 40px en bas au lieu de 100px */
        }

        .faq-section {
            padding: 40px 0 100px !important; /* 40px en haut au lieu de 100px */
        }

        .discovery-card {
            padding: 48px;
        }
        .discovery-grid { 
            flex-direction: row; 
            text-align: left; 
            justify-content: space-between;
            gap: 40px; 
        }
        .discovery-text {
            text-align: left !important;
        }
        .btn-discovery-premium { 
            width: auto; 
        }

        .premium-goal-card {
            padding: 40px;
        }
        .step-number {
            font-size: 3.5rem;
            top: 20px;
            right: 30px;
        }

        .premium-testimonial-card {
            padding: 44px;
        }
        .quote-mark {
            left: 24px;
            top: 10px;
        }

        .faq-header {
            padding: 24px 28px;
            font-size: 1.1rem;
        }
        .faq-body {
            padding: 0 28px 28px;
        }
    }
</style>

@section('content')

    <div id="top"></div>

    @php
        $data = $beforeHome ? json_decode($beforeHome->content) : null;
    @endphp

    @if($data)
    <section class="hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8 hero-content-box mx-auto">
                    
                    <div class="hero-eyebrow">
                        {!! htmlspecialchars_decode($data->eyebrow ?? 'Conseil opérationnel · TPE / PME / ETI') !!}
                    </div>

                    <div class="hero-main-title">
                        @php
                            $cleanBigText = $data->big_text ?? '<h1>Vous savez que votre entreprise perd de l\'argent. <em style="color: rgb(212, 172, 94);">Vous ne savez pas exactement où, ni combien.</em></h1>';
                            $cleanBigText = preg_replace('/<p>&nbsp;<\/p>|<p><br><\/p>/', '', $cleanBigText);
                        @endphp
                        {!! htmlspecialchars_decode($cleanBigText) !!}
                    </div>
                    
                    <div class="hero-description">
                        @php
                            $cleanSmallText = $data->small_text ?? '<p>Nous, si. Audit financier, social et organisationnel — puis accompagnement opérationnel payé uniquement sur résultats. Diagnostic confidentiel en 30 minutes.</p>';
                            $cleanSmallText = preg_replace('/<p><br><\/p>|<p>&nbsp;<\/p>|\s+$/', '', $cleanSmallText);
                        @endphp
                        {!! htmlspecialchars_decode($cleanSmallText) !!}
                    </div>

                    <div class="hero-actions">
                        <a href="{{ $data->button_primary_link ?? route('contact') }}" class="btn-primary-gold">
                            {{ $data->button_primary_text ?? 'Prendre un rendez-vous gratuit' }} <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="#strategy" class="btn-outline-white">
                            {{ $data->button_secondary_text ?? 'Découvrir notre méthode' }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    @endif

    @if($videoBlock)
        @php $vData = json_decode($videoBlock->content); @endphp
        <section class="video-section bg-dark-premium">
            <div class="container">
                <div class="video-container mx-auto">
                    <div class="video-wrapper">
                        <video 
                            class="main-video" 
                            controls 
                            playsinline
                            poster="{{ asset($videoBlock->image_path) }}"
                            preload="metadata">
                            <source src="{{ asset($videoBlock->video_path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </div>
                    @if(!empty($vData->title))
                        <p class="video-caption">
                            {{ $vData->title }}
                        </p>
                    @endif

                    <div class="text-center mt-4 mt-md-5">
                        <a href="{{ route('contact') }}" class="btn-primary-gold w-100 d-sm-inline-flex w-sm-auto">
                            <span>Prendre un rendez-vous gratuitement</span>
                            <i class="bi bi-calendar3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="insights-discovery-section bg-dark-premium">
            <div class="container">
                <div class="discovery-card" data-aos="fade-up">
                    <div class="discovery-grid">
                        <div class="discovery-text">
                            <span class="discovery-badge">Expertise & Analyses</span>
                            <h3 class="discovery-title">Explorez notre veille stratégique</h3>
                            <p class="discovery-subtitle m-0">Décryptages et leviers de croissance pour les dirigeants.</p>
                        </div>
                        <div class="discovery-action w-100 w-lg-auto">
                            <a href="{{ url('/insights') }}" class="btn-discovery-premium">
                                <span>Consulter nos analyses</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($goals->count() > 0)
    <section class="goals-section bg-dark-premium relative" id="strategy">
        <div class="container relative">
            <div class="text-center mb-4 mb-md-5" data-aos="fade-down">
                <span class="text-uppercase tracking-wider font-bold text-sm" style="color: var(--primary);">Un Objectif ! VOUS !</span>
                <h2 class="section-title font-normal mt-2">Votre trajectoire financière</h2>
                <p class="mt-3 max-w-2xl mx-auto text-base text-md-lg">Une approche structurée pour transformer votre gestion financière en levier de croissance.</p>
            </div>

            <div class="row g-4 mt-2 mt-md-4">
                @foreach($goals as $index => $goal)
                    @php $gData = json_decode($goal->content); @endphp
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                        <div class="premium-goal-card">
                            <div class="step-number">0{{ $loop->iteration }}</div>
                            
                            @if($goal->image_path)
                                <div class="goal-icon-wrapper">
                                    <img src="{{ asset($goal->image_path) }}" alt="Coach entreprise - {{ $gData->title }}" class="goal-img">
                                </div>
                            @endif

                            <h3 class="text-xl font-bold mb-3">
                                {{ $gData->title ?? 'Étape' }}
                            </h3>
                            
                            <div class="leading-relaxed text-sm">
                                {!! $gData->description ?? $goal->content !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($meetGoals)
                @php $mText = json_decode($meetGoals->content); @endphp
                <div class="mt-4 mt-md-5 pt-2 pt-md-4" data-aos="zoom-in">
                    <div class="engagement-banner">
                        <div class="row align-items-center p-4 p-md-5">
                            <div class="col-lg-8 text-lg-start text-center">
                                <span class="badge-premium">{{ $mText->badge ?? 'Sans engagement' }}</span>
                                <h3 class="text-2xl text-md-3xl font-normal mt-3">{{ $mText->title ?? 'Prêt à optimiser votre rentabilité ?' }}</h3>
                                <div class="mt-2 mb-4 mb-lg-0">{!! $mText->description ?? '' !!}</div>
                            </div>
                            <div class="col-lg-4 text-center text-lg-end">
                                <a href="{{ route('contact') }}" class="btn-primary-gold w-100 w-lg-auto">
                                    <span>Je me lance</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    @endif

    @if($testimonials->count() > 0)
    <section id="testimonials" class="testimonials-section bg-dark-premium">
        <div class="container">
            <div class="text-center pt-1 mb-4 mb-md-5" data-aos="fade-down">
                <span class="text-uppercase tracking-wider font-bold text-sm" style="color: var(--primary);">Ils nous ont choisis</span>
                <h2 class="section-title font-normal mt-2">Vos Témoignages</h2>
                <p class="mt-3 max-w-xl mx-auto text-base text-md-lg">Découvrez comment nous accompagnons nos clients vers la sérénité financière.</p>
            </div>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
                <div class="carousel-inner">
                    @foreach($testimonials->chunk(2) as $index => $chunk)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="row g-4 justify-content-center">
                            @foreach($chunk as $testi)
                                @php $tData = json_decode($testi->content); @endphp
                                <div class="col-lg-6">
                                    <div class="premium-testimonial-card h-100 text-start">
                                        <div class="quote-mark">“</div>
                                        <div class="testimonial-text">{!! $tData->comment !!}</div>
                                        
                                        <div class="testimonial-profile">
                                            @if($testi->image_path)
                                                <img src="{{ asset($testi->image_path) }}" alt="{{ $tData->name }}" class="author-avatar">
                                            @endif
                                            <div class="author-details">
                                                <h4 class="author-name text-white">{{ $tData->name }}</h4>
                                                <p class="author-role m-0" style="color: var(--primary); font-size:0.9rem;">{{ $tData->role }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4 mt-md-5">
                    <button class="carousel-nav-btn" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="carousel-nav-btn" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($faqs->count() > 0)
    <section id="faq" class="faq-section bg-dark-premium">
        <div class="container">
            <div class="text-center mb-4 mb-md-5" data-aos="fade-down">
                <span class="text-uppercase tracking-wider font-bold text-sm" style="color: var(--primary);">Des réponses à vos questions</span>
                <h2 class="section-title font-normal mt-2">FAQ</h2>
            </div>

            <div class="faq-grid mx-auto mt-2 mt-md-4" style="max-width: 840px;">
                @foreach($faqs as $faq)
                    @php $fData = json_decode($faq->content); @endphp
                    <details class="premium-faq-item text-start" data-aos="fade-up">
                        <summary class="faq-header">
                            <span class="faq-title">{{ $fData->title }}</span>
                            <div class="faq-icon">
                                <i class="bi bi-plus-lg"></i>
                            </div>
                        </summary>
                        <div class="faq-content">
                            <div class="faq-body">
                                {!! $fData->description !!}
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection