@extends('layouts.app')

@section('title', 'Audit Financier - Prendre Rendez-vous')
@section('meta_description', 'Réservez votre audit financier de 30 minutes. Choisissez votre créneau pour faire le point sur la trésorerie et la rentabilité de votre TPE/PME.')

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
    <section class="audit-section pt-32 pb-20 bg-[#020617] min-h-screen relative overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px]"></div>

        <div class="container relative ">
            <div class="max-w-4xl mx-auto mb-16">
                <div class="flex justify-between relative">
                    <div class="absolute top-1/2 left-0 w-full h-px bg-white/10 -translate-y-1/2"></div>
                    <div id="progress-line" class="absolute top-1/2 left-0 h-0.5 bg-blue-500 -translate-y-1/2 transition-all duration-700" style="width: 0%"></div>
                    
                    @foreach(['Date', 'Profil', 'Activité', 'Objectifs'] as $index => $step)
                    <div class="step-item flex flex-col items-center relative z-10 {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                        <div class="step-circle w-12 h-12 rounded-full border-2 border-white/10 bg-[#020617] flex items-center justify-center font-bold text-white/30 transition-all duration-500">
                            {{ $index + 1 }}
                        </div>
                        <span class="absolute -bottom-8 text-[10px] uppercase tracking-[0.2em] text-white/20 font-semibold whitespace-nowrap">{{ $step }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="max-w-5xl mx-auto">
                <div class="audit-card-premium backdrop-blur-2xl bg-white/[0.02] border border-white/10 rounded-[2.5rem] p-8 md:p-14 shadow-2xl relative">

                    <form id="auditForm" novalidate>
                        @csrf
                        
                        <div class="form-step active" data-step="1">
                            <div class="mb-12">
                                <h2 class="text-4xl font-bold text-white tracking-tight mb-3">Choisissez votre créneau</h2>
                                <p class="text-white/40 text-lg">Sélectionnez une date disponible sur le calendrier.</p>
                            </div>
                            
                            <input type="hidden" name="meeting_date" id="meeting_date" required>
                            <input type="hidden" name="meeting_hour" id="meeting_hour" required>

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                                <div class="lg:col-span-7">
                                    <div class="calendar-wrapper bg-white/[0.03] p-8 rounded-3xl border border-white/5">
                                        <div class="flex justify-between items-center mb-10">
                                            <button type="button" class="btn-cal-nav p-3 hover:bg-white/10 rounded-xl text-white transition-colors">&lt;</button>
                                            <span class="month-year text-xl font-bold text-white uppercase tracking-widest"></span>
                                            <button type="button" class="btn-cal-nav p-3 hover:bg-white/10 rounded-xl text-white transition-colors">&gt;</button>
                                        </div>
                                        <div class="grid grid-cols-7 gap-4 mb-6 text-center text-[11px] font-black text-blue-500/60 uppercase tracking-tighter">
                                            <div>Lun</div><div>Mar</div><div>Mer</div><div>Jeu</div><div>Ven</div><div>Sam</div><div>Dim</div>
                                        </div>
                                        <div id="calendarGrid" class="grid grid-cols-7 gap-3"></div>
                                    </div>
                                </div>
                                
                                <div class="lg:col-span-5">
                                    <h3 class="text-white/80 font-semibold mb-6 flex items-center gap-3">
                                        <span class="w-8 h-px bg-blue-500"></span>
                                        Heures disponibles
                                    </h3>
                                    <div id="timeGrid" class="grid grid-cols-2 gap-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-step" data-step="2">
                            <div id="fields-container-step2">
                                <div class="mb-12">
                                    <h2 class="text-4xl font-bold text-white tracking-tight mb-3">Vos coordonnées</h2>
                                    <p class="text-white/40 text-lg">Comment pouvons-nous vous recontacter ?</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-3">
                                        <label class="text-sm font-bold text-white/50 ml-1">NOM *</label>
                                        <input type="text" name="nom" required class="premium-input" placeholder="Ex: Martin">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-sm font-bold text-white/50 ml-1">PRÉNOM *</label>
                                        <input type="text" name="prenom" required class="premium-input" placeholder="Ex: Thomas">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-sm font-bold text-white/50 ml-1">TÉLÉPHONE *</label>
                                        <input type="tel" name="tel" required class="premium-input" placeholder="06 .. .. .. ..">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-sm font-bold text-white/50 ml-1">EMAIL *</label>
                                        <input type="email" name="email" id="email_input" required class="premium-input" placeholder="thomas@societe.com">
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        <div class="form-step" data-step="3">
                            <div class="text-center mb-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-500/10 text-blue-400 mb-4 border border-blue-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white">Confirmation du mail</h3>
                                <p class="text-white/60 mt-2">Un code de validation a été envoyé à votre adresse. Veuillez le saisir ci-dessous.</p>
                            </div>

                            <div class="max-w-xs mx-auto">
                                <div class="relative group">
                                    <input type="text" id="otp_input" maxlength="6" placeholder="000000" 
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-5 text-center text-4xl tracking-[0.5em] font-mono text-blue-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-white/5">
                                </div>
                                
                                <p id="otp-error" class="text-red-400 text-xs mt-3 text-center hidden italic">Code incorrect. Veuillez réessayer.</p>

                                <div class="mt-8 text-center">
                                    <button type="button" id="resend-otp-btn" disabled 
                                            class="text-sm font-medium text-white/40 transition-all hover:text-white disabled:cursor-not-allowed">
                                        Renvoyer le code <span id="resend-timer"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-step" data-step="4">
                            <h2 class="text-3xl font-bold text-white mb-2">Détails de l'entreprise</h2>
                            <p class="text-white/50 mb-8">Parlez-nous de votre structure et de votre modèle financier.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Nom de la société *</label>
                                    <input type="text" name="company_name" required class="premium-input" placeholder="Ex: Eau2L Digital">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Type de société *</label>
                                    <select name="company_type" required class="premium-input appearance-none bg-[#020617]">
                                        <option value="" disabled selected>Sélectionnez une forme juridique</option>
                                        <option value="EI">Entrepreneur individuel (EI)</option>
                                        <option value="EURL">Entreprise unipersonnelle (EURL)</option>
                                        <option value="SARL">Société à responsabilité limitée (SARL)</option>
                                        <option value="SASU">Société par actions simplifiée (SASU)</option>
                                        <option value="SAS">Société par actions simplifiée (SAS)</option>
                                        <option value="SA">Société anonyme (SA)</option>
                                        <option value="SNC">Société en nom collectif (SNC)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Activité de la société (Optionnel)</label>
                                    <select name="company_activity" class="premium-input appearance-none bg-[#020617]">
                                        <option value="" selected>Choisir un secteur</option>
                                        <option value="Commerce">Commerce de produits</option>
                                        <option value="E-commerce">E-commerce</option>
                                        <option value="Immobilier">Immobilier</option>
                                        <option value="Finance">Finance, banque</option>
                                        <option value="CHR">Hôtellerie, restauration</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Marge théorique (%) *</label>
                                    <input type="text" name="marge_theorique" required class="premium-input" placeholder="Ex: 30%">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Chiffre d'affaires *</label>
                                    <input type="text" name="ca" required class="premium-input" placeholder="Ex: 500k€">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Nombre de salariés</label>
                                    <input type="text" name="employees" class="premium-input" placeholder="Ex: 12">
                                </div>
                            </div>
                            <div class="mt-6">
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Bilan / Observations *</label>
                                <textarea name="bilan" rows="3" required class="premium-input" placeholder="Résumé de votre dernier bilan..."></textarea>
                            </div>
                        </div>

                        <div class="form-step" data-step="5">
                            <h2 class="text-3xl font-bold text-white mb-2">Vos objectifs</h2>
                            <p class="text-white/50 mb-8">Précisez vos attentes pour ce rendez-vous.</p>

                            <div class="mb-8">
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Qu'attendez-vous ? *</label>
                                <textarea name="user_expectations" required class="premium-input" rows="3" placeholder="Décrivez vos besoins..."></textarea>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                <label class="objective-card group">
                                    <input type="radio" name="rdv_objective" value="Analyse et préconisation" class="hidden" required>
                                    <div class="objective-content">
                                        <span class="text-sm font-medium">Analyse financière et préconisation</span>
                                        <div class="check-circle"></div>
                                    </div>
                                </label>
                                <label class="objective-card group">
                                    <input type="radio" name="rdv_objective" value="Accompagnement opérationnel" class="hidden">
                                    <div class="objective-content">
                                        <span class="text-sm font-medium">Accompagnement opérationnel</span>
                                        <div class="check-circle"></div>
                                    </div>
                                </label>
                                <label class="objective-card group">
                                    <input type="radio" name="rdv_objective" value="Les deux" class="hidden">
                                    <div class="objective-content">
                                        <span class="text-sm font-medium">Les deux</span>
                                        <div class="check-circle"></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="error-banner" class="hidden mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-2xl text-red-500 text-sm flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">Veuillez remplir tous les champs obligatoires (*).</span>
                        </div>

                        <div class="flex justify-between items-center mt-8 pt-8 border-t border-white/5">
                            <button type="button" id="prevBtn" class="hidden flex items-center justify-center gap-2 px-6 py-4 rounded-full text-white/50 hover:text-white hover:bg-white/5 transition-all border border-white/10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                <span class="hidden md:block font-bold uppercase tracking-widest text-xs">Précédent</span>
                            </button>
                            
                            <button type="button" id="nextBtn" class="ml-auto flex items-center justify-center gap-3 px-8 md:px-10 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/20 transition-all transform active:scale-95">
                                <span class="uppercase tracking-widest text-xs" id="nextBtnText">Suivant</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div id="success-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div id="modal-card" class="relative bg-[#0a0a0a] border border-white/10 p-8 rounded-2xl max-w-sm w-full text-center shadow-2xl transform transition-all scale-95 opacity-0">
            <div class="mb-4 flex justify-center">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center text-green-500">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Bravo !</h3>
            <p class="text-gray-400 mb-6">Votre rendez-vous est enregistré.</p>
            <button onclick="window.location.href='/'" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg">Retour à l'accueil</button>
        </div>
    </div>

<style>
/* TOUT TON CSS D'ORIGINE */
.form-step { display: none; }
.form-step.active { display: block; animation: fadeIn 0.5s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.invalid-card { border-color: rgba(239, 68, 68, 0.5) !important; background-color: rgba(239, 68, 68, 0.05) !important; animation: shake 0.4s ease-in-out; }
@keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

.step-circle.bg-blue-600 { box-shadow: 0 0 15px rgba(37, 99, 235, 0.4); }
.objective-card input:checked + .objective-content { border-color: #3b82f6; background: rgba(59, 130, 246, 0.1); }
.premium-input:invalid:not(:placeholder-shown) { border-color: rgba(239, 68, 68, 0.5); }

.objective-card.!border-red-500\/50 {
    border-color: rgb(239 68 68 / 0.5) !important;
}
/* Animation de secousse pour l'erreur */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-8px); }
    75% { transform: translateX(8px); }
}
.animate-shake { animation: shake 0.4s ease-in-out; }

/* Classe d'erreur pour les cartes d'objectifs */
.objective-card.invalid-selection {
    border-color: rgba(239, 68, 68, 0.6) !important;
    background: rgba(239, 68, 68, 0.05) !important;
}

/* On force le bandeau d'erreur à être bien visible */
#error-banner:not(.hidden) {
    display: flex !important;
}
.objective-card.error-border {
    border-color: rgb(239 68 68 / 0.5) !important; /* Rouge */
    background-color: rgb(239 68 68 / 0.05) !important; /* Fond léger rouge */
}

/* Repère minimaliste pour le jour actuel */
.cal-day.is-today {
    border: 2px solid #3b82f6 !important; /* Bordure blanche pour trancher */
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2); /* Petit halo discret */
    opacity: 1 !important; /* On le garde bien visible */
}
</style>

@endsection

@push('scripts')
    @vite(['resources/js/audit-form.js'])
@endpush