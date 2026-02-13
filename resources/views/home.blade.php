@extends('layouts.app')

@section('title', 'Accueil')

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

            <h1 class="hero-title">
                {!! htmlspecialchars_decode($data->big_text ?? '') !!}
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
                                    <img src="{{ asset($goal->image_path) }}" alt="Icon" class="goal-img">
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

@endsection