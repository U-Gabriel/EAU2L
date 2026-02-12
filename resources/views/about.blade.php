@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
    <div class="container my-5 py-5 text-center">
        <h1 class="display-4 fw-bold">À Propos</h1>
        <p class="lead text-muted">Bienvenue sur la page de présentation de notre entreprise.</p>
        
        <hr class="my-4 w-25 mx-auto">

        {{-- Ici tu pourras boucler sur tes blocs plus tard comme sur la Home --}}
        @if(isset($page) && $page->blocks->count() > 0)
             {{-- Ton code de boucle pour les blocs ici --}}
        @else
            <div class="alert alert-light border shadow-sm p-4 mt-4">
                Cette page est en cours de construction. Revenez bientôt !
            </div>
        @endif
    </div>
@endsection