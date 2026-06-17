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

@section('content')
<style>
    :root {
        /* Palette de couleurs professionnelle fixe */
        --primary-gold: #C5973B;
        --hero-bg: #0F1B2D;          
        --light-bg: #F8FAFC;         
        --white: #FFFFFF;
        
        /* Typographies couleurs contrastées */
        --text-dark: #0F172A;        
        --text-muted: #475569;       
        --text-light: #F8FAFC;       
        --text-gray-hero: #94A3B8;   
        --border-light: #E2E8F0;     
    }

    /* Application globale Pro */
    body, .main-content { 
        background-color: var(--light-bg) !important; 
        color: var(--text-dark); 
        font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    
    /* Typographies Professionnelles */
    h1, h2, h3, h4 { 
        font-family: 'DM Serif Display', Georgia, serif;
        font-weight: 400;
    }
    p { 
        font-family: 'DM Sans', sans-serif;
    }

    .section-title {
        font-size: clamp(2rem, 4vw, 3rem) !important;
        line-height: 1.25;
        color: var(--text-dark) !important;
    }

    /* --- SECTION HERO (BLEU #0F1B2D IMMERSIF) --- */
    .hero {
        padding: 140px 0 100px !important; 
        background: var(--hero-bg) !important;
        position: relative;
        overflow: hidden !important;
    }

    .hero-content-box {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        justify-content: center !important;
    }

    .hero-eyebrow {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        color: var(--primary-gold) !important;
        font-family: 'DM Sans', sans-serif !important;
        font-size: clamp(0.75rem, 2.5vw, 0.85rem) !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 2px !important;
        margin-bottom: 24px !important;
        width: 100%;
        flex-wrap: wrap;
    }

    .hero-eyebrow::before {
        content: '' !important;
        width: 20px !important;
        height: 2px !important;
        background: var(--primary-gold) !important;
        display: inline-block;
    }

    .hero-main-title { width: 100%; }
    .hero-main-title h1 {
        color: var(--text-light) !important;
        font-size: clamp(2rem, 5.5vw, 3.5rem) !important;
        line-height: 1.25 !important;
        margin-bottom: 24px !important;
        text-align: center !important;
    }

    .hero-main-title h1 em, .hero-main-title h1 span[style*="color"] {
        font-style: italic !important;
        color: var(--primary-gold) !important;
    }

    .hero-description { width: 100%; }
    .hero-description p, .hero-description span {
        font-size: clamp(1rem, 2.8vw, 1.2rem) !important;
        color: var(--text-gray-hero) !important;
        line-height: 1.6;
        text-align: center !important;
    }

    .hero-actions {
        display: flex !important;
        flex-direction: column !important;
        gap: 14px !important;
        justify-content: center !important;
        margin-top: 36px !important;
        width: 100%;
        max-width: 420px;
    }

    /* Boutons Institutionnels Premium */
    .btn-primary-gold {
        background: var(--primary-gold) !important;
        color: var(--hero-bg) !important;
        font-family: 'DM Sans', sans-serif !important;
        font-weight: 700 !important;
        padding: 16px 32px !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        font-size: 1rem !important;
        transition: all 0.3s ease !important;
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
        color: var(--hero-bg) !important;
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

    /* --- SECTION : SITUATIONS D'INTERVENTION --- */
    .situations-section {
        padding: 50px 0 !important;
        background: var(--light-bg) !important;
    }
    .situation-card {
        background: var(--white) !important;
        border: 1px solid var(--border-light) !important;
        border-radius: 16px;
        padding: 32px 24px;
        display: flex;
        gap: 20px;
        align-items: flex-start;
        height: 100%;
        transition: all 0.3s ease;
    }
    .situation-card:hover {
        border-color: var(--primary-gold) !important;
        box-shadow: 0 12px 30px rgba(197, 151, 59, 0.08);
        transform: translateY(-3px);
    }
    .situation-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(197, 151, 59, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .situation-card h4 {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
    .situation-card p {
        font-size: 0.92rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }
    
    /* Section Vidéo */
    .video-section {
        padding: 60px 0 80px !important;
        background: var(--light-bg) !important;
    }
    .video-container {
        max-width: 960px;
        position: relative;
    }
    .video-wrapper {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border-light);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
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
        color: var(--text-muted);
        font-style: italic;
    }

    /* Section Découverte des Insights / Veille */
    .insights-discovery-section {
        padding: 20px 0 60px !important;
        background: var(--light-bg) !important;
    }
    .discovery-card {
        background: var(--white) !important;
        border: 1px solid var(--border-light) !important;
        border-radius: 24px;
        padding: 32px 24px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }
    .discovery-grid {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 24px;
    }
    .discovery-badge {
        color: var(--primary-gold);
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 2px;
        margin-bottom: 10px;
        display: block;
    }
    .discovery-title {
        color: var(--text-dark) !important;
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        margin-bottom: 10px;
    }
    .discovery-subtitle {
        color: var(--text-muted) !important;
    }
    .btn-discovery-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: var(--hero-bg) !important;
        color: var(--white) !important;
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-discovery-premium:hover {
        background: var(--primary-gold) !important;
        color: var(--hero-bg) !important;
        transform: translateX(4px);
    }

    /* ========================================================================= */
    /* CHRONOLOGIE PREMIUM ULTRA-DYNAMIQUE (ALTERNEE GAUCHE / DROITE & PAS-A-PAS)*/
    /* ========================================================================= */
    .method-section {
        padding: 50px 0 !important;
        background: #F8FAFC !important;
        position: relative;
        overflow: hidden;
    }

    .timeline-container {
        position: relative;
        max-width: 1100px;
        margin: 64px auto 0;
        padding: 0 15px;
    }

    /* Ligne verticale de liaison centrale épurée */
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 31px;
        top: 24px;
        bottom: 24px;
        width: 2px;
        background: #E2E8F0;
        z-index: 1;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 48px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    /* Conteneur du macaron de numérotation */
    .timeline-badge-wrapper {
        position: absolute;
        left: 4px;
        top: 14px;
        z-index: 3;
    }

    .timeline-badge {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: var(--hero-bg) !important;
        color: var(--primary-gold) !important;
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 400;
        box-shadow: 0 4px 15px rgba(15, 27, 45, 0.12);
        border: 2px solid var(--white);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Cartes d'Étapes Pro et Aérées */
    .timeline-card {
        background: var(--white) !important;
        border: 1px solid var(--border-light) !important;
        border-radius: 20px !important;
        padding: 36px;
        width: calc(100% - 85px);
        margin-left: 85px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.02);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    /* Micro-interactions dynamiques au survol */
    .timeline-item:hover .timeline-card {
        transform: translateY(-4px);
        border-color: var(--primary-gold) !important;
        box-shadow: 0 20px 45px rgba(197, 151, 59, 0.12);
    }

    .timeline-item:hover .timeline-badge {
        background: var(--primary-gold) !important;
        color: var(--hero-bg) !important;
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 20px rgba(197, 151, 59, 0.35);
    }

    .timeline-card h3 {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.45rem;
        color: var(--text-dark) !important;
        margin-bottom: 12px;
        font-weight: 400;
    }

    .timeline-card p {
        color: var(--text-muted) !important;
        font-size: 1rem;
        line-height: 1.7;
        margin: 0;
    }

    /* On cible l'em à l'intérieur du titre principal pour réduire sa taille */
    .hero .hero-main-title h1 {
        font-size: 3.5rem !important; /* Utilisez des rem ou px, évitez em ici */
        line-height: 1.2 !important;
    }

    /* ========================================================= */
    /* --- NOUVEAU DESIGN STATIQUE : NOTRE MÉTHODE (MAQUETTE) ---*/
    /* ========================================================= */
    .new-method-section {
        padding: 30px 0;
        background: var(--white);
    }
    .new-method-label {
        color: var(--primary-gold);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        margin-bottom: 12px;
    }
    .steps-container { margin-top: 56px; }
    .step-row {
        display: grid;
        grid-template-columns: 72px 1fr;
        gap: 0 32px;
        padding-bottom: 48px;
        position: relative;
    }
    .step-row:last-child { padding-bottom: 0; }
    .step-num-col {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .step-num {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--hero-bg);
        color: var(--primary-gold);
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
    }
    .step-line {
        flex: 1;
        width: 2px;
        background: var(--border-light);
        margin-top: 8px;
    }
    .step-row:last-child .step-line { display: none; }
    .step-content { padding-top: 12px; }
    
    .expertise-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-top: 24px;
    }
    .expertise-card {
        background: var(--light-bg);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 28px 22px;
        border-top: 3px solid var(--primary-gold);
        transition: transform 0.2s;
    }
    .expertise-card:hover { transform: translateY(-3px); }
    .expertise-card h4 {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.05rem;
        color: var(--text-dark);
        margin-bottom: 10px;
    }
    .expertise-card p {
        font-size: 0.87rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    .about-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: center;
        margin-top: 48px;
    }

    .about-text p {
        color: #555B6E;
        font-size: .95rem;
        margin-bottom: 16px;
        line-height: 1.7;
    }

    .about-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 32px;
    }

    .stat-box {
        background: #F7F8FA;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .stat-num {
        font-family: 'DM Serif Display', serif;
        font-size: 2rem;
        color: #C5973B;
    }

    .stat-label {
        font-size: .82rem;
        color: #555B6E;
        margin-top: 4px;
    }

    .about-right {
        background: #0F1B2D;
        border-radius: 16px;
        padding: 48px 36px;
        color: #fff;
    }

    .about-right h3 {
        font-family: 'DM Serif Display', serif;
        font-size: 1.4rem;
        margin-bottom: 20px;
        color: #D4AC5E;
    }

    .about-right ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .about-right li {
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,.08);
        font-size: .92rem;
        color: rgba(255,255,255,.75);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .about-right li:before {
        content: "→";
        color: #C5973B;
        font-weight: 700;
    }

    #about .section-title{
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .about-layout {
            grid-template-columns: 1fr;
            gap: 32px;
        }
    }

    @media (max-width: 768px) {
        .step-row { grid-template-columns: 56px 1fr; gap: 0 20px; }
    }


    /* --- RENDER DESKTOP GRAND ÉCRAN : EFFET ASYMÉTRIQUE GAUCHE / DROITE --- */
    @media (min-width: 992px) {
        .timeline-container::before {
            left: 50%;
            transform: translateX(-50%);
        }

        .timeline-item {
            flex-direction: row;
            justify-content: space-between;
        }

        .timeline-badge-wrapper {
            left: 50%;
            transform: translateX(-50%);
            top: 24px;
        }

        .timeline-card {
            width: 45%;
            margin-left: 0;
        }

        /* Distribution alternée */
        .timeline-item.item-odd {
            justify-content: flex-start;
        }

        .timeline-item.item-even {
            justify-content: flex-end;
        }
        
        /* Flèches indicatrices géométriques pros */
        .timeline-item.item-odd .timeline-card::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 40px;
            border-width: 10px 0 10px 10px;
            border-style: solid;
            border-color: transparent transparent transparent var(--white);
            transition: all 0.4s ease;
        }
        
        .timeline-item.item-even .timeline-card::after {
            content: '';
            position: absolute;
            left: -10px;
            top: 40px;
            border-width: 10px 10px 10px 0;
            border-style: solid;
            border-color: transparent var(--white) transparent transparent;
            transition: all 0.4s ease;
        }

        /* Dynamisme de couleur sur les flèches directionnelles au survol */
        .timeline-item.item-odd:hover .timeline-card::after {
            border-color: transparent transparent transparent var(--primary-gold);
        }
        .timeline-item.item-even:hover .timeline-card::after {
            border-color: transparent var(--primary-gold) transparent transparent;
        }
    }

    /* Bannière d'engagement finale */
    .engagement-banner {
        background: var(--hero-bg) !important; 
        border-radius: 24px;
        overflow: hidden;
        margin-top: 64px;
    }
    .engagement-banner h3 {
        color: var(--white) !important;
    }
    .engagement-banner div {
        color: var(--text-gray-hero) !important;
    }
    .badge-premium {
        background: rgba(197, 151, 59, 0.2);
        color: var(--primary-gold);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Section Témoignages */
    .testimonials-section {
        padding: 100px 0 80px !important;
        background: var(--light-bg) !important;
    }
    .testimonials-section p {
        color: var(--text-muted) !important;
    }
    .premium-testimonial-card {
        background: var(--white) !important;
        border: 1px solid var(--border-light) !important;
        padding: 32px 24px;
        border-radius: 24px;
        position: relative;
        margin: 0 10px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
    }
    .quote-mark {
        font-family: 'DM Serif Display', serif;
        font-size: 5rem;
        color: var(--primary-gold) !important;
        position: absolute;
        top: 5px;
        left: 16px;
        opacity: 0.2;
    }
    .testimonial-text {
        font-size: 1.05rem;
        line-height: 1.7;
        color: var(--text-dark) !important;
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
        border: 2px solid var(--primary-gold);
    }
    .author-name {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark) !important;
    }
    .carousel-nav-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid var(--border-light);
        background: var(--white);
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .carousel-nav-btn:hover {
        background: var(--primary-gold);
        color: var(--hero-bg);
        border-color: var(--primary-gold);
    }

    /* Section FAQ */
    .faq-section {
        padding: 80px 0 !important;
        background: var(--white) !important;
    }
    .premium-faq-item {
        background: var(--light-bg) !important;
        border: 1px solid var(--border-light) !important;
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
        color: var(--text-dark);
        list-style: none;
        gap: 15px;
    }
    .faq-header::-webkit-details-marker { display: none; }
    .faq-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.05);
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    details[open] .faq-icon {
        transform: rotate(45deg);
        background: var(--primary-gold);
        color: var(--hero-bg);
    }
    .faq-body {
        padding: 0 20px 20px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    /* --- SECTION TARIFS (TRANSPARENCE) --- */
    .tarifs-section {
        padding: 30px 0 !important;
        background: var(--light-bg) !important;
    }
    .section-label {
        color: var(--primary-gold);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        margin-bottom: 12px;
    }
    .section-subtitle {
        color: var(--text-muted);
        font-size: 1.05rem;
        max-width: 600px;
        line-height: 1.65;
    }
    .tarifs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-top: 48px;
    }
    .tarif-card {
        background: var(--white);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        padding: 36px 28px;
        position: relative;
        transition: transform 0.25s, box-shadow 0.25s;
    }
    .tarif-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.06);
    }
    .tarif-card.featured {
        border: 2px solid var(--primary-gold);
        box-shadow: 0 8px 32px rgba(197, 151, 59, 0.12);
    }
    .tarif-badge {
        position: absolute;
        top: -12px;
        left: 28px;
        background: var(--primary-gold);
        color: var(--hero-bg);
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 14px;
        border-radius: 20px;
        letter-spacing: 1px;
    }
    .tarif-card h4 {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.2rem;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
    .tarif-price {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--hero-bg);
        margin-bottom: 6px;
    }
    .tarif-price span {
        font-size: 0.85rem;
        color: var(--text-gray-hero);
        font-weight: 500;
    }
    .tarif-desc {
        font-size: 0.88rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .tarif-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .tarif-list li {
        padding: 8px 0;
        font-size: 0.87rem;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    .tarif-list li:last-child { border-bottom: none; }
    .tarif-list li::before {
        content: '✓';
        color: #2E7D56;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* --- GENERAL BREAKPOINTS --- */
    @media (min-width: 992px) {
        .hero { 
            padding: 180px 0 130px !important; 
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
        .hero-eyebrow::before { width: 30px !important; }
        .hero-main-title h1, .hero-description p { text-align: left !important; }
        
        .hero-main-title h1 { font-size: clamp(2.5rem, 5vw, 3.8rem) !important; }
        .hero-description p { font-size: clamp(1.1rem, 2.5vw, 1.25rem) !important; }
        
        .hero-actions { 
            flex-direction: row !important; 
            max-width: none !important;
            gap: 16px !important;
            margin-top: 40px !important;
        }
        .hero-actions .btn-primary-gold, 
        .hero-actions .btn-outline-white { width: auto !important; }
        
        .situations-section, .video-section { padding: 90px 0 !important; }
        .testimonials-section { padding: 90px 0 50px !important; }
        .faq-section { padding: 50px 0 100px !important; }

        .discovery-card { padding: 48px; }
        .discovery-grid { 
            flex-direction: row; 
            text-align: left; 
            justify-content: space-between;
            gap: 40px; 
        }
        .discovery-text { text-align: left !important; }
        .btn-discovery-premium { width: auto; }

        .premium-testimonial-card { padding: 44px; }
        .quote-mark { left: 24px; top: 10px; }
        
        .faq-header { padding: 24px 28px; font-size: 1.1rem; }
        .faq-body { padding: 0 28px 28px; }
    }
</style>

    <div id="top"></div>

    @php
        $data = $beforeHome ? json_decode($beforeHome->content) : null;
    @endphp

    {{-- 1. SECTION HERO (BEFORE HOME) --}}
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
                            $cleanBigText = $data->big_text ?? '<h1 style="font-size: 0.7em">Vous savez que votre entreprise perd de l\'argent. <em style="color: rgb(212, 172, 94);">Vous ne savez pas exactement où, ni combien.</em></h1>';
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
                        <a href="#methode" class="btn-outline-white">
                            {{ $data->button_secondary_text ?? 'Découvrir notre méthode' }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 2. SECTION : LES SITUATIONS D'INTERVENTION --}}
    @if(isset($situations) && $situations->count() > 0)
    <section class="situations-section" id="problemes">
        <div class="container">
            <div class="text-center text-lg-start mb-4 mb-md-5" data-aos="fade-down">
                <span class="text-uppercase tracking-wider font-bold text-sm" style="color: var(--primary-gold); letter-spacing: 2px; font-weight: 700;">Vous vous reconnaissez ?</span>
                <h2 class="section-title mt-2 mb-3">Les situations dans lesquelles nous intervenons</h2>
                <p class="m-0 max-w-2xl text-base text-muted" style="font-size: 1.05rem;">Ces situations ne sont pas irréversibles. Elles nécessitent une intervention rapide et structurée.</p>
            </div>

            <div class="row g-4">
                @foreach($situations as $index => $sit)
                    @php 
                        $sData = json_decode($sit->content); 
                    @endphp
                    @if($sData && !empty($sData->title))
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <div class="situation-card">
                            <div class="situation-icon">{!! $sData->icon ?? '📊' !!}</div>
                            <div>
                              <h4>{!! $sData->title !!}</h4>
                              <p>{!! $sData->description ?? '' !!}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- 3. SECTION VIDEO & INSIGHTS --}}
    @if($videoBlock)
        @php $vData = json_decode($videoBlock->content); @endphp
        <section class="video-section">
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

        <section class="insights-discovery-section">
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


    {{-- ============================================================== --}}
    {{-- NOUVELLE SECTION : NOTRE MÉTHODE (Issue de la maquette HTML) --}}
    {{-- ============================================================== --}}
    <section class="new-method-section" id="strategy">
        <div class="container">
            <div class="new-method-label text-center text-lg-start">Notre méthode</div>
            <h2 class="section-title text-center text-lg-start">3 étapes. 90 jours. Des résultats mesurables.</h2>
            <p class="text-muted text-center text-lg-start mx-auto mx-lg-0" style="font-size: 1.05rem; max-width: 600px; line-height: 1.65;">
                Une approche structurée, calibrée pour les dirigeants qui veulent agir — pas attendre.
            </p>

            <div class="steps-container">
                <div class="step-row" data-aos="fade-up">
                    <div class="step-num-col">
                        <div class="step-num">01</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-content">
                        <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.35rem; color: var(--text-dark); margin-bottom: 12px;">Rendez-vous découverte</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 580px;">Échange de 30 minutes, en ligne ou en présentiel. On analyse votre situation : tensions de trésorerie, baisse de marge, pression sociale ou organisationnelle. À l'issue, nous définissons ensemble si un audit est pertinent — et lequel.</p>
                        <p style="margin-top:10px; color: var(--primary-gold); font-weight: 600; font-size: 0.88rem;">Gratuit · Sans engagement · Confidentiel</p>
                    </div>
                </div>

                <div class="step-row" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-num-col">
                        <div class="step-num">02</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-content">
                        <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.35rem; color: var(--text-dark); margin-bottom: 12px;">Audit — Phase 1</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 580px;">Diagnostic financier, social et organisationnel complet avec préconisations chiffrées. Tarif indexé à la taille de votre entreprise.</p>
                        
                        <div class="expertise-grid">
                            <div class="expertise-card">
                                <h4>Audit financier & comptable</h4>
                                <p>Diagnostic de rentabilité, analyse des coûts, détection des fuites de marge, tableaux de bord de pilotage.</p>
                            </div>
                            <div class="expertise-card">
                                <h4>Audit social & RH</h4>
                                <p>Conformité URSSAF, optimisation masse salariale, accompagnement juridique, plans de restructuration.</p>
                            </div>
                            <div class="expertise-card">
                                <h4>Accompagnement opérationnel</h4>
                                <p>Réduction des coûts, modernisation des process, recouvrement de créances, négociations fournisseurs.</p>
                            </div>
                            <div class="expertise-card">
                                <h4>Redressement & retournement</h4>
                                <p>Plan de retournement structuré, stabilisation de trésorerie, restauration de la rentabilité.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="step-row" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-num-col">
                        <div class="step-num">03</div>
                    </div>
                    <div class="step-content">
                        <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.35rem; color: var(--text-dark); margin-bottom: 12px;">Accompagnement opérationnel — Phase 2</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 580px;">Mise en œuvre des recommandations pendant 90 jours minimum. Rémunérée <strong>uniquement sur les résultats obtenus</strong> chaque mois. Zéro honoraire fixe sur cette phase.</p>
                        <p style="margin-top:12px; color: var(--text-gray-hero); font-size: 0.92rem;">Contrairement à un administrateur judiciaire, nous intervenons en toute discrétion — vos clients, fournisseurs et salariés ne sont pas informés.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<section class="tarifs-section" id="tarifs">
    <div class="container">
        <div class="text-center text-lg-start mb-5" data-aos="fade-down">
            <span class="section-label">Transparence</span>
            <h2 class="section-title font-normal mt-2 mb-3">Nos tarifs</h2>
            <p class="section-subtitle mx-auto mx-lg-0">Pas de devis opaque. Vous savez exactement ce que vous payez, à chaque étape.</p>
        </div>

        <div class="phase-container mb-5" data-aos="fade-up">
            <div class="phase-header-block">
                <div class="phase-badge">Phase 01</div>
                <h3 class="phase-title">Diagnostic & Audit Stratégique</h3>
                <p class="phase-subtitle">Analyse approfondie et préconisations chiffrées. Tarif fixe selon la taille de votre structure.</p>
            </div>

            <div class="tarifs-grid mt-4">
                <div class="tarif-card">
                    <h4>Audit · TPE</h4>
                    <div class="tarif-price">4&nbsp;500 – 8&nbsp;000&nbsp;€ <span>HT</span></div>
                    <div class="tarif-desc">Chiffre d'affaires inférieur à 1 M€</div>
                    <ul class="tarif-list">
                        <li>Analyse financière complète</li>
                        <li>Diagnostic de rentabilité</li>
                        <li>Détection des pertes de marge</li>
                        <li>Plan d'actions priorisé</li>
                    </ul>
                </div>

                <div class="tarif-card featured">
                    <div class="tarif-badge">Le plus fréquent</div>
                    <h4>Audit · PME</h4>
                    <div class="tarif-price">8&nbsp;000 – 15&nbsp;000&nbsp;€ <span>HT</span></div>
                    <div class="tarif-desc">Chiffre d'affaires entre 1 M€ et 10 M€</div>
                    <ul class="tarif-list">
                        <li>Audit financier détaillé</li>
                        <li>Audit social et RH</li>
                        <li>Analyse organisationnelle</li>
                        <li>Préconisations chiffrées</li>
                    </ul>
                </div>

                <div class="tarif-card">
                    <h4>Audit · ETI</h4>
                    <div class="tarif-price">15&nbsp;000 – 40&nbsp;000&nbsp;€ <span>HT</span></div>
                    <div class="tarif-desc">Chiffre d'affaires entre 10 M€ et 50 M€</div>
                    <ul class="tarif-list">
                        <li>Audit multidisciplinaire</li>
                        <li>Analyse des risques</li>
                        <li>Optimisation financière</li>
                        <li>Plan de retournement complet</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="phase-container" data-aos="fade-up" data-aos-delay="100">
            <div class="phase-header-block">
                <div class="phase-badge badge-gold">Phase 02</div>
                <h3 class="phase-title">Accompagnement Opérationnel</h3>
                <div class="phase-price-highlight">Rémunéré uniquement sur résultats</div>
                <p class="phase-subtitle max-w-720">
                    Aucun honoraire fixe. Le pourcentage est défini contractuellement à l'issue de l'audit. 
                    Engagement initial de 90 jours, résiliable ensuite par simple e-mail, sans préavis ni pénalité.
                </p>
            </div>
        </div>
    </div>
</section>

<style>
    /* Structure globale des blocs de phases */
    .phase-container {
        background: var(--white, #ffffff);
        border: 2px solid var(--primary-gold, #c6973b);
        border-radius: 16px;
        padding: 40px 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .phase-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(198, 151, 59, 0.08);
    }

    /* En-tête des blocs de phase */
    .phase-header-block {
        text-align: center;
        margin-bottom: 20px;
    }

    .phase-badge {
        display: inline-block;
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        background: rgba(13, 21, 39, 0.05);
        color: var(--hero-bg, #0d1527);
        padding: 6px 16px;
        border-radius: 50px;
        margin-bottom: 14px;
    }

    .phase-badge.badge-gold {
        background: var(--primary-gold, #c6973b);
        color: #ffffff;
    }

    .phase-title {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.75rem;
        color: var(--text-dark, #0d1527);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .phase-subtitle {
        font-size: 0.95rem;
        color: var(--text-muted, #6c757d);
        margin: 0 auto;
    }

    .max-w-720 {
        max-width: 720px;
        line-height: 1.6;
    }

    /* Mise en avant du prix orienté performance de la phase 2 */
    .phase-price-highlight {
        font-family: 'Inter', sans-serif;
        font-size: 1.25rem;
        color: var(--primary-gold, #c6973b);
        font-weight: 700;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Correction des marges internes de la grille de cartes */
    .tarifs-section .tarifs-grid {
        gap: 24px;
    }
</style>
  <section class="section" id="about">
    <div class="container">

        <div class="section-label">
            Qui sommes-nous
        </div>

        <h2 class="section-title">
            Un cabinet fondé par un praticien du redressement
        </h2>

        <div class="about-layout">

            <div>

                <div class="about-text">

                    <p>
                        ARMATURE Business accompagne les dirigeants de TPE,
                        PME et ETI en Île-de-France confrontés à des difficultés
                        financières, sociales ou organisationnelles.
                    </p>

                    <p>
                        Notre fondateur cumule près de 10 ans d'expérience
                        dans de grands groupes de services français :
                        juriste en droit social, directeur de centre de profit
                        puis directeur de projets de retournement.
                    </p>

                    <p>
                        Pas de rapport théorique.
                        Des actions terrain avec des résultats mesurables.
                    </p>

                </div>

                <div class="about-stats">

                    <div class="stat-box">
                        <div class="stat-num">15</div>
                        <div class="stat-label">
                            Établissements redressés
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-num">10 ans</div>
                        <div class="stat-label">
                            D'expérience opérationnelle
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-num">&lt; 90j</div>
                        <div class="stat-label">
                            Pour des résultats visibles
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-num">100%</div>
                        <div class="stat-label">
                            Confidentiel
                        </div>
                    </div>

                </div>

            </div>

            <div class="about-right">

                <h3>Pourquoi nous, pas un autre ?</h3>

                <ul>
                    <li>
                        Alignement d'intérêts — Phase 2 payée sur résultats
                    </li>

                    <li>
                        Discrétion totale — aucun signal extérieur
                    </li>

                    <li>
                        Double compétence finance + social
                    </li>

                    <li>
                        Intervention rapide — 48h après validation
                    </li>

                    <li>
                        Zéro engagement au-delà de 90 jours
                    </li>

                </ul>

            </div>

        </div>

    </div>
</section>

</section>

    @if($goals->count() > 0)
    <section id="strategy" class="method-section" style="padding: 2rem 0;">
        <div class="container">
            <div class="engagement-banner p-4 p-md-5 text-center text-md-start" data-aos="zoom-in">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <span class="badge-premium mb-3 d-inline-block">Engagement de résultats</span>
                        <h3 class="text-white font-normal mb-2">Prêt à sécuriser votre trésorerie ?</h3>
                        <div class="text-gray-hero">Bénéficiez d'un premier état des lieux gratuit et sans engagement pour faire le point sur votre rentabilité.</div>
                    </div>
                    <div class="col-lg-4 text-md-center text-lg-end">
                        <a href="/contact" class="btn-primary-gold px-4 py-3">Prendre rendez-vous <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif


    {{-- 5. TÉMOIGNAGES --}}
    @if($testimonials->count() > 0)
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <div class="text-center pt-1 mb-4 mb-md-5" data-aos="fade-down">
                <span class="text-uppercase tracking-wider font-bold text-sm" style="color: var(--primary-gold);">Ils nous ont choisis</span>
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
                                                <h4 class="author-name">{{ $tData->name }}</h4>
                                                <p class="author-role m-0" style="color: var(--primary-gold); font-size:0.9rem;">{{ $tData->role }}</p>
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

    {{-- 6. FAQ --}}
    @if($faqs->count() > 0)
    <section id="faq" class="faq-section">
        <div class="container">
            <div class="text-center mb-4 mb-md-5" data-aos="fade-down">
                <span class="text-uppercase tracking-wider font-bold text-sm" style="color: var(--primary-gold);">Des réponses à vos questions</span>
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
<style>
        /* Permet à la carte de respirer et de déborder vers le haut au survol */
    .carousel-inner {
        overflow: visible !important;
    }

    .carousel-item {
        overflow: visible !important;
    }

    /* Optionnel : Ajoute une marge interne en haut de la section pour éviter */
    /* que la carte ne vienne frôler ou passer sous le titre au survol */
    .testimonials-section {
        padding-top: 6rem !important; 
    }

    .faq-title {
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--text-dark);
    }

</style>
@endsection