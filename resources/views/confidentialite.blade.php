@extends('layouts.app')

@section('title', 'Confidentialité | Eau2L Digital')

@section('content')
<div class="bg-[#020617] min-h-screen pt-32 pb-24 px-6 relative overflow-hidden">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-white text-5xl font-extrabold tracking-tight mb-16">Data <span class="text-blue-500">Protection</span></h1>

        <div class="space-y-6">
            @foreach([
                ['title' => 'Collecte des données', 'content' => 'Nous recueillons vos informations (nom, email, besoins stratégiques) via le formulaire d\'audit pour traiter vos demandes d\'accompagnement.'],
                ['title' => 'Finalité du traitement', 'content' => 'Vos données ne sont jamais revendues. Elles servent uniquement à l\'établissement d\'un diagnostic lors de l\'audit et au suivi commercial.'],
                ['title' => 'Sécurité', 'content' => 'Vos données sont stockées sur des serveurs sécurisés et cryptées via le protocole SSL (HTTPS).'],
                ['title' => 'Vos Droits (RGPD)', 'content' => 'Conformément au RGPD, vous disposez d\'un droit de suppression et de portabilité de vos données.']
            ] as $section)
            <div class="group bg-white/[0.02] hover:bg-white/[0.04] border border-white/[0.05] p-8 rounded-2xl transition-all duration-300">
                <h3 class="text-white text-lg font-semibold mb-3 flex items-center justify-between">
                    {{ $section['title'] }}
                    <span class="text-blue-500/30 group-hover:text-blue-500 transition-colors">0{{ $loop->iteration }}</span>
                </h3>
                <p class="text-gray-500 text-sm leading-relaxed font-light">
                    {{ $section['content'] }}
                    @if($loop->last)
                        Contactez-nous à : <span class="text-red-500 font-bold underline">[À REMPLIR : EMAIL]</span>
                    @endif
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection