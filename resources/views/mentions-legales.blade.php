@extends('layouts.app')

@section('title', 'Mentions Légales | Eau2L Digital')

@section('content')
<div class="bg-[#020617] min-h-screen pt-32 pb-24 px-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/5 blur-[120px] rounded-full"></div>

    <div class="max-w-5xl mx-auto relative z-10">
        <header class="mb-16 border-b border-white/5 pb-8 text-left">
            <h1 class="text-white text-5xl font-extrabold tracking-tight mb-4">Mentions <span class="text-blue-500">Légales</span></h1>
            <p class="text-gray-500 font-light">Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white/[0.02] border border-white/[0.05] p-8 rounded-2xl">
                <h2 class="text-white text-lg font-bold uppercase tracking-widest mb-6 flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span> Édition
                </h2>
                <div class="space-y-4 text-gray-400 text-sm leading-relaxed">
                    <p>Le présent site est édité par :</p>
                    <p><strong class="text-white uppercase tracking-tighter">Société :</strong> <span class="text-red-500 font-bold">[À REMPLIR : EX: EAU2L DIGITAL SAS]</span></p>
                    <p><strong class="text-white uppercase tracking-tighter">Siège :</strong> <span class="text-red-500 font-bold">[À REMPLIR : ADRESSE COMPLÈTE]</span></p>
                    <p><strong class="text-white uppercase tracking-tighter">SIREN :</strong> <span class="text-red-500 font-bold">[À REMPLIR : NUMÉRO]</span></p>
                    <p><strong class="text-white uppercase tracking-tighter">Contact :</strong> <span class="text-red-500 font-bold">[À REMPLIR : EMAIL]</span></p>
                </div>
            </div>

            <div class="bg-white/[0.02] border border-white/[0.05] p-8 rounded-2xl">
                <h2 class="text-white text-lg font-bold uppercase tracking-widest mb-6 flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span> Hébergement
                </h2>
                <div class="space-y-4 text-gray-400 text-sm leading-relaxed">
                    <p>Le site est hébergé techniquement par :</p>
                    <p><strong class="text-white uppercase tracking-tighter">Hébergeur :</strong> <span class="text-red-500 font-bold">[À REMPLIR : EX: HOSTINGER]</span></p>
                    <p><strong class="text-white uppercase tracking-tighter">Localisation :</strong> <span class="text-red-500 font-bold">[À REMPLIR : FRANCE / EUROPE]</span></p>
                    <p>L'hébergeur assure la sécurité physique et logicielle des serveurs.</p>
                </div>
            </div>

            <div class="md:col-span-2 bg-white/[0.02] border border-white/[0.05] p-8 rounded-2xl">
                <h2 class="text-white text-lg font-bold uppercase tracking-widest mb-6">Propriété Intellectuelle</h2>
                <p class="text-gray-400 text-sm leading-relaxed">
                    L'ensemble des contenus (logos, graphismes, interfaces, textes) présents sur ce site est la propriété exclusive de <span class="text-white">Eau2L Digital</span>. Toute reproduction, distribution ou modification de ces éléments est strictement interdite sans notre accord écrit préalable.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection