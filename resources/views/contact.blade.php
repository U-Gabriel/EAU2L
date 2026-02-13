@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <section class="audit-section pt-32 pb-20 bg-[#020617] min-h-screen relative overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px]"></div>

        <div class="container relative z-10">
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
                                    <div id="timeGrid" class="grid grid-cols-2 gap-4">
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-step" data-step="2">
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
                                    <input type="email" name="email" required class="premium-input" placeholder="thomas@societe.com">
                                </div>
                            </div>
                        </div>

                        <div class="form-step" data-step="3">
                        <h2 class="text-3xl font-bold text-white mb-2">Détails de l'entreprise</h2>
                        <p class="text-white/50 mb-8">Parlez-nous de votre structure et de votre modèle financier.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Nom de la société *</label>
                                <input type="text" name="company_name" required placeholder="Ex: Eau2L Digital">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Type de société *</label>
                                <select name="company_type" required>
                                    <option value="" disabled selected>Sélectionnez une forme juridique</option>
                                    <option value="EI">Entrepreneur individuel (EI)</option>
                                    <option value="EURL">Entreprise unipersonnelle (EURL)</option>
                                    <option value="SARL">Société à responsabilité limitée (SARL)</option>
                                    <option value="SASU">Société par actions simplifiée (SASU)</option>
                                    <option value="SAS">Société par actions simplifiée (SAS)</option>
                                    <option value="SA">Société anonyme (SA)</option>
                                    <option value="SNC">Société en nom collectif (SNC)</option>
                                    <option value="SCS">Société en commandite simple (SCS)</option>
                                    <option value="SCA">Société en commandite par actions (SCA)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Activité de la société (Optionnel)</label>
                                <select name="company_activity">
                                    <option value="" selected>Choisir un secteur</option>
                                    <option value="Commerce">Commerce de produits de grande consommation</option>
                                    <option value="E-commerce">Commerce électronique, vente hors magasin</option>
                                    <option value="Immobilier">Immobilier, logement</option>
                                    <option value="Energie">Energie, eau, assainissement</option>
                                    <option value="BTP">Travaux du bâtiment, aménagement</option>
                                    <option value="Transport">Transport voyageurs / marchandises</option>
                                    <option value="Vehicules">Véhicules</option>
                                    <option value="Finance">Finance, banque, assurance</option>
                                    <option value="Communication">Communication, téléphonie</option>
                                    <option value="CHR">Hôtellerie, restauration</option>
                                    <option value="Tourisme">Tourisme, voyage</option>
                                    <option value="Loisirs">Culture, loisirs, sport</option>
                                    <option value="Bricolage">Bricolage, jardinage, animaux</option>
                                    <option value="SAP">Produits et services à la personne</option>
                                    <option value="Enseignement">Enseignement</option>
                                    <option value="Juridique">Services juridiques</option>
                                    <option value="Assistance">Services d'assistance</option>
                                    <option value="Franchise">Franchise</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Marge théorique (%) *</label>
                                <input type="text" name="marge_theorique" required placeholder="Ex: 30%">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Chiffre d'affaires *</label>
                                <input type="text" name="ca" required placeholder="Ex: 500k€">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Nombre de salariés (Optionnel)</label>
                                <input type="text" name="employees" placeholder="Ex: 12">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">Bilan / Observations *</label>
                            <textarea name="bilan" rows="3" required placeholder="Résumé de votre dernier bilan ou points clés..."></textarea>
                        </div>
                    </div>

                        <div class="form-step" data-step="4">
                            <h2 class="text-3xl font-bold text-white mb-2">Vos objectifs</h2>
                            <p class="text-white/50 mb-8">Précisez vos attentes pour ce rendez-vous.</p>

                            <div class="mb-8">
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-2">
                                    Qu'attendez-vous à l'issue de ce point ? *
                                </label>
                                <textarea name="user_expectations" required rows="3" 
                                        placeholder="Décrivez brièvement vos besoins spécifiques..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-4">
                                    Type d'intervention souhaité *
                                </label>
                                
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
                        </div>

                       <div id="error-banner" class="hidden mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-2xl text-red-500 text-sm flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Bravo !</h3>
            <p class="text-gray-400 mb-6">
                Votre rendez-vous est enregistré, et un mail vous a été envoyé.<br>
                <span class="text-xs italic mt-4 block opacity-60">
                    Redirection dans <span id="countdown-text">5</span>s...
                </span>
            </p>
            <button onclick="window.location.href='/'" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg">
                Retour à l'accueil
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/audit-form.js'])
@endpush