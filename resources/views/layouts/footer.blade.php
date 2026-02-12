<footer class="relative bg-[#020617] pt-20 pb-10 border-t border-white/[0.05]">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-[1px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-start">
            
            <div class="md:col-span-4 flex flex-col items-start space-y-6">
                <img src="{{ asset('images/logo3.png') }}" alt="Logo" class="h-9 w-auto brightness-110">
                <p class="text-gray-400 text-sm leading-relaxed font-light">
                    Expertise digitale et accompagnement stratégique pour propulser votre croissance vers de nouveaux sommets.
                </p>
            </div>

            <div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-white text-[11px] font-bold uppercase tracking-[0.3em] mb-8 opacity-90">Navigation</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="/" 
                            style="color: #6b7280 !important; text-decoration: none !important;" 
                            class="text-[13px] transition-all duration-300 hover:text-white">
                            Accueil
                            </a>
                        </li>
                        <li>
                            <a href="/contact" 
                            style="color: #6b7280 !important; text-decoration: none !important;" 
                            class="text-[13px] transition-all duration-300 hover:text-white">
                            Prendre RDV
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white text-[11px] font-bold uppercase tracking-[0.3em] mb-8 opacity-90">Services</h4>
                    <ul class="space-y-4">
                        <li><a href="/#strategy" style="color: #6b7280 !important; text-decoration: none !important;"  class="text-gray-500 hover:text-white text-[13px] no-underline transition-all duration-300">Stratégie</a></li>
                        <li><a href="/#testimonials" style="color: #6b7280 !important; text-decoration: none !important;"  class="text-gray-500 hover:text-white text-[13px] no-underline transition-all duration-300">Témoignages</a></li>
                        <li><a href="/#faq" style="color: #6b7280 !important; text-decoration: none !important;"  class="text-gray-500 hover:text-white text-[13px] no-underline transition-all duration-300">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white text-[11px] font-bold uppercase tracking-[0.3em] mb-8 opacity-90">Légal</h4>
                    <ul class="space-y-4">
                        <li><a href="/mentions-legales" class="text-gray-500 hover:text-white text-[13px] no-underline transition-all duration-300">Mentions Légales</a></li>
                        <li><a href="/confidentialite" class="text-gray-500 hover:text-white text-[13px] no-underline transition-all duration-300">Confidentialité</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-20 pt-8 border-t border-white/[0.03] flex flex-col md:flex-row justify-between items-center">
            <span class="text-gray-600 text-[10px] uppercase tracking-[0.2em]">© {{ date('Y') }} Eau2L Digital</span>
        </div>
    </div>
</footer>