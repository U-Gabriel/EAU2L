@extends('layouts.app')

@section('title', 'Expert en Trésorerie et Rentabilité TPE/PME | Armature Business')

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

@section('meta_description', 'Optimisez votre gestion financière et boostez la rentabilité de votre entreprise avec Armature Business. Accompagnement stratégique sur mesure pour dirigeants.')

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

    @php
        $data = $beforeHome ? json_decode($beforeHome->content) : null;
    @endphp

    @if($data)
    <section class="hero-home">
        <div class="container hero-content">
            
            @if(!empty($data->button_primary_text))
            <div class="hero-badge-container">
                <a href="{{ $data->button_primary_link ?? '#' }}" class="btn btn-primary">
                    {!! $data->button_primary_text !!}
                    <i class="bi bi-arrow-right-short"></i> </a>
            </div>
            @endif

            <!--<h1 class="hero-title">
                {!! htmlspecialchars_decode($data->big_text ?? '') !!}
            </h1>-->

            <h1 class="hero-title">
                <span class="d-none">Conseil en gestion de trésorerie : </span>
                {!! htmlspecialchars_decode($data->big_text ?? 'Optimisez votre rentabilité') !!}
            </h1>
            
            <p class="hero-subtitle mx-auto" style="max-width: 700px; opacity: 0.9;">
                {!! htmlspecialchars_decode($data->small_text ?? '') !!}
            </p>

        </div>
    </section>
    @endif

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

                    <div class="text-center mt-5">
                        <a href="{{ route('contact') }}" class="btn-audit">
                            Prendre un rendez-vous gratuitement
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>

                    <section class="insights-discovery-section">
                        <div class="container">
                            <div class="discovery-card" data-aos="fade-up">
                                <div class="discovery-grid">
                                    <div class="discovery-text">
                                        <span class="discovery-badge">Expertise & Analyses</span>
                                        <h3 class="discovery-title">Explorez notre veille stratégique</h3>
                                        <p class="discovery-subtitle">Décryptages et leviers de croissance pour les dirigeants.</p>
                                    </div>
                                    <div class="discovery-action">
                                        <a href="{{ url('/insights') }}" class="btn-discovery-premium">
                                            <span>Consulter nos analyses</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    @endif

    @if($goals->count() > 0)
   <section class="goals-section pt-0 pb-24 bg-[#020617] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/10 blur-[120px] -z-10"></div>
        
        <div class="container relative" id="strategy">
            <div class="text-center mb-20" data-aos="fade-down">
                <span class="text-blue-500 font-bold tracking-widest uppercase text-sm">Un Objectif ! VOUS !</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mt-3">Votre trajectoire financière</h2>
                <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Une approche structurée pour transformer votre gestion financière en levier de croissance.</p>
            </div>

            <div class="row g-4 relative">
                <div class="hidden lg:block absolute top-[40%] left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-blue-500/20 to-transparent -z-10"></div>
                @foreach($goals as $index => $goal)
                    @php $gData = json_decode($goal->content); @endphp
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                        <div class="premium-goal-card group">
                            <div class="step-number">0{{ $loop->iteration }}</div>
                            
                            @if($goal->image_path)
                                <div class="goal-icon-wrapper">
                                    <img src="{{ asset($goal->image_path) }}" alt="Coach entreprise - {{ $gData->title }}" class="goal-img">
                                </div>
                            @endif

                            <h3 class="text-xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors">
                                {{ $gData->title ?? 'Étape' }}
                            </h3>
                            
                            <div class="text-gray-400 leading-relaxed text-sm">
                                {!! $gData->description ?? $goal->content !!}
                            </div>
                            
                            <div class="card-glow"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($meetGoals)
                @php $mText = json_decode($meetGoals->content); @endphp
                <div class="mt-10" data-aos="zoom-in">
                    <div class="engagement-banner">
                        <div class="row align-items-center p-4 p-md-5">
                            <div class="col-lg-8 text-lg-start text-center">
                                <span class="badge-premium">{{ $mText->badge ?? 'Sans engagement' }}</span>
                                <h3 class="text-3xl font-bold text-white mt-3">{{ $mText->title ?? 'Prêt à optimiser votre rentabilité ?' }}</h3>
                                <div class="text-gray-400 mt-2 mb-lg-0 mb-4">{!! $mText->description ?? '' !!}</div>
                            </div>
                            <div class="col-lg-4 text-center text-lg-end">
                                <a href="{{ route('contact') }}" class="btn-premium-gold">
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
    <section id="testimonials" class="testimonials-section bg-[#020617] pt-0 pb-24 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-10 left-10 w-64 h-64 bg-blue-600 blur-[120px]"></div>
        </div>

        <div class="container relative z-10">
            <div class="text-center mb-16" data-aos="fade-down">
                <span class="text-blue-500 font-bold tracking-widest uppercase text-sm">Ils nous ont choisis</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mt-3">Vos Témoignages</h2>
                <p class="text-gray-400 mt-4 max-w-xl mx-auto">Découvrez comment nous accompagnons nos clients vers la sérénité financière.</p>
            </div>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
                <div class="carousel-inner">
                    @foreach($testimonials->chunk(2) as $index => $chunk)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="row g-4 justify-content-center">
                            @foreach($chunk as $testi)
                                @php $tData = json_decode($testi->content); @endphp
                                <div class="col-lg-5">
                                    <div class="premium-testimonial-card h-100">
                                        <div class="quote-mark">“</div>
                                        <div class="testimonial-text">{!! $tData->comment !!}</div>
                                        
                                        <div class="testimonial-profile">
                                            @if($testi->image_path)
                                                <img src="{{ asset($testi->image_path) }}" alt="{{ $tData->name }}" class="author-avatar">
                                            @endif
                                            <div class="author-details">
                                                <h4 class="author-name">{{ $tData->name }}</h4>
                                                <p class="author-role">{{ $tData->role }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center gap-3 mt-12">
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
    <section id="faq" class="faq-section bg-[#020617] pb-24">
        <div class="container">
            <div class="text-center mb-16" data-aos="fade-down">
                <span class="text-blue-500 font-bold tracking-widest uppercase text-sm">Des réponses à vos questions</span>
                <h2 class="text-4xl font-extrabold text-white mt-3">FAQ</h2>
            </div>

            <div class="faq-grid mx-auto" style="max-width: 900px;">
                @foreach($faqs as $faq)
                    @php $fData = json_decode($faq->content); @endphp
                    <details class="premium-faq-item" data-aos="fade-up">
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
        .insights-discovery-section {
            padding: 60px 0;
            background: #020617;
        }

        .discovery-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.3) 0%, rgba(15, 23, 42, 0.5) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 40px;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .discovery-card:hover {
            border-color: rgba(59, 130, 246, 0.3);
        }

        .discovery-grid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }

        .discovery-badge {
            color: #3b82f6;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 12px;
            display: block;
        }

        .discovery-title {
            color: #ffffff;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .discovery-subtitle {
            color: #94a3b8;
            font-size: 1rem;
            margin: 0;
            opacity: 0.8;
        }

        /* Le Bouton Premium */
        .btn-discovery-premium {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: #ffffff; /* Bouton blanc pour trancher radicalement */
            color: #020617;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-discovery-premium:hover {
            background: #3b82f6;
            color: #ffffff;
            transform: translateX(5px);
        }

        .btn-discovery-premium i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-discovery-premium:hover i {
            transform: translateX(3px);
        }

        /* Adaptabilité Mobile */
        @media (max-width: 992px) {
            .discovery-grid {
                flex-direction: column;
                text-align: center;
                gap: 30px;
            }
            
            .discovery-card {
                padding: 40px 25px;
                margin: 0 15px;
            }

            .btn-discovery-premium {
                width: 100%;
                justify-content: center;
            }

            .discovery-title {
                font-size: 1.5rem;
            }
        }
    </style>




@endsection

