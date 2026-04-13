@extends('layouts.app')
@section('title', 'Actualités & Stratégies')
@section('content')
<div class="container py-10" style="max-width: 800px; margin-top: 100px;">
    <header class="mb-5">
        <span class="text-blue-500 fw-bold font-mono">ANALYSE // {{ $post->date_creation }}</span>
        <h1 class="display-4 text-white fw-bold mt-3">{{ $post->title }}</h1>
    </header>
    <div class="text-white-50 fs-5 leading-relaxed">
        {!! $post->description !!}
    </div>
</div>
@endsection