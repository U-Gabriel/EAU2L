@extends('layouts.app')

{{-- SEO Dynamique --}}
@section('title', $post->title . ' | Armature Business')
@section('meta_description', Str::limit(strip_tags($post->description), 150))

@if($post->pictures && $post->pictures->first())
    @section('og_image', asset($post->pictures->first()->path_location))
@endif

@php
    // Construction dynamique du schéma JSON-LD pour Google Rich Snippets
    $articleSchema = [
        "@context" => "https://schema.org",
        "@type" => "BlogPosting",
        "headline" => $post->title,
        "description" => Str::limit(strip_tags($post->description), 150),
        "datePublished" => \Carbon\Carbon::parse($post->date_creation)->toIso8601String(),
        "author" => [
            "@type" => "Organization",
            "name" => "Armature Business",
            "url" => url('/')
        ],
        "publisher" => [
            "@type" => "Organization",
            "name" => "Armature Business",
            "logo" => [
                "@type" => "ImageObject",
                "url" => asset('images/logo_armature.png')
            ]
        ]
    ];

    if($post->pictures && $post->pictures->first()) {
        $articleSchema["image"] = asset($post->pictures->first()->path_location);
    }
@endphp

@push('scripts')
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<div class="container py-10" style="max-width: 800px; margin-top: 100px; min-height: 80vh;">
    <header class="mb-5">
        <a href="{{ url('/insights') }}" class="text-blue-500 fw-bold font-mono text-uppercase mb-3 d-inline-block" style="text-decoration: none; font-size: 0.85rem; letter-spacing: 1px;">
            <i class="bi bi-arrow-left me-1"></i> Retour aux analyses
        </a>
        <br>
        <span class="text-blue-500 fw-bold font-mono">
            ANALYSE // {{ \Carbon\Carbon::parse($post->date_creation)->translatedFormat('d F Y') }}
        </span>
        <h1 class="display-4 text-white fw-bold mt-3">{{ $post->title }}</h1>
    </header>
    
    <div class="text-white-50 fs-5 leading-relaxed full-article-body">
        {!! $post->description !!}
    </div>
</div>

<style>
    .full-article-body img {
        display: block;
        margin: 40px auto; 
        max-width: 100%;    
        border-radius: 8px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
    }
    .full-article-body p { margin-bottom: 1.5rem; color: rgba(255,255,255,0.7); }
</style>
@endsection