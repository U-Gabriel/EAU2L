let currentStep = 1;
let formDataStorage = {};

document.addEventListener('DOMContentLoaded', function() {
    // 1. SELECTEURS & INIT
    const inputDate = document.getElementById('meeting_date');
    const inputHour = document.getElementById('meeting_hour');
    const grid = document.getElementById('calendarGrid'); 
    const timeGrid = document.getElementById('timeGrid');
    const btnNext = document.getElementById('nextBtn');
    const btnPrev = document.getElementById('prevBtn');
    let errorBanner = document.getElementById('error-banner');
    
    // Correction : On cible la nouvelle classe de ton design premium
    const auditCard = document.querySelector('.audit-card-premium');

    if (!grid || !btnNext || !inputDate) return;

    // Reset initial
    const form = document.getElementById('auditForm');
    if (form) form.reset();
    inputDate.value = "";
    inputHour.value = "";
    let currentNavDate = new Date();
    let offDays = [];

    fetch('/api/off-days')
        .then(res => res.json())
        .then(data => { 
            offDays = data; 
            renderCalendar(currentNavDate); 
        });

    // 2. BANDEAU D'ERREUR (Vérifie s'il existe déjà dans le HTML avant de le créer)
    if (!errorBanner) {
        errorBanner = document.createElement('div');
        errorBanner.id = "error-banner";
        // On utilise les classes qui matchent ton CSS final
        errorBanner.className = "mb-4 animate-in"; 
        errorBanner.innerHTML = `
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span class="font-medium text-xs">Veuillez remplir tous les champs obligatoires (*).</span>
            </div>`;
        
        // AU LIEU DE PREPEND : On l'insère juste avant la zone des boutons
        const buttonContainer = document.querySelector('.flex.justify-between.items-center.mt-8');
        if (buttonContainer) {
            buttonContainer.parentNode.insertBefore(errorBanner, buttonContainer);
        } else if (auditCard) {
            auditCard.appendChild(errorBanner);
        }
    }

    // 3. GESTION DU CALENDRIER
    grid.addEventListener('click', function(e) {
        const dayDiv = e.target.closest('.cal-day');
        if (dayDiv && !dayDiv.classList.contains('empty') && !dayDiv.classList.contains('disabled')) {
            const dayValue = dayDiv.textContent;
            const monthYearText = document.querySelector('.month-year').textContent.split(' ');
            const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            const mIndex = monthNames.indexOf(monthYearText[0]) + 1;
            const fullDate = `${monthYearText[1]}-${String(mIndex).padStart(2, '0')}-${String(dayValue).padStart(2, '0')}`;

            document.querySelectorAll('.cal-day').forEach(d => d.classList.remove('active', 'bg-blue-600', 'border-blue-500'));
            dayDiv.classList.add('active', 'bg-blue-600', 'border-blue-500');

            inputDate.value = fullDate;
            inputHour.value = "";
            errorBanner.classList.add('hidden');
            loadTimeSlots(fullDate);
        }
    });

    function renderCalendar(date) {
        grid.innerHTML = '';
        const year = date.getFullYear();
        const month = date.getMonth();
        const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        document.querySelector('.month-year').textContent = `${monthNames[month]} ${year}`;

        let firstDay = new Date(year, month, 1).getDay();
        let offset = (firstDay === 0) ? 6 : firstDay - 1;
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let x = 0; x < offset; x++) {
            grid.insertAdjacentHTML('beforeend', '<div class="cal-day empty opacity-0"></div>');
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'cal-day transition-all duration-300 hover:scale-105 cursor-pointer p-2 sm:p-4 rounded-lg text-center text-white font-medium border border-white/5 bg-white/5';
            dayDiv.textContent = i;
            
            const checkDate = new Date(year, month, i);
            const fullDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

            if (checkDate < today || checkDate.getDay() === 0 || checkDate.getDay() === 6 || offDays.includes(fullDateStr)) {
                dayDiv.classList.add('disabled', 'opacity-20');
                dayDiv.style.pointerEvents = 'none';
            }else {
                fetch(`/api/has-slots?date=${fullDateStr}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.available) {
                            dayDiv.classList.add('disabled', 'opacity-20');
                            dayDiv.style.pointerEvents = 'none';
                        }
                    }).catch(() => {});
            }
            if (inputDate.value === fullDateStr) dayDiv.classList.add('active', 'bg-blue-600', 'border-blue-500');
            grid.appendChild(dayDiv);
        }
    }

    function loadTimeSlots(dateStr) {
        timeGrid.innerHTML = "<div class='col-span-2 text-white/30 text-sm animate-pulse'>Chargement...</div>";
        fetch(`/api/available-slots?date=${dateStr}`)
            .then(res => res.json())
            .then(slots => {
                timeGrid.innerHTML = "";
                if (!slots || slots.length === 0) {
                    timeGrid.innerHTML = "<div class='col-span-2 text-red-400 text-xs p-3'>Aucun créneau.</div>";
                    return;
                }
                slots.forEach(slot => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = "time-slot p-3 rounded-lg border border-white/5 bg-white/5 text-white text-center cursor-pointer hover:bg-white/10 transition-all text-sm";
                    slotDiv.textContent = slot;
                    if (inputHour.value === slot) slotDiv.classList.add('selected', 'border-blue-500', 'bg-blue-600/20');
                    slotDiv.onclick = () => {
                        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected', 'border-blue-500', 'bg-blue-600/20'));
                        slotDiv.classList.add('selected', 'border-blue-500', 'bg-blue-600/20');
                        inputHour.value = slot;
                        errorBanner.classList.add('hidden');
                    };
                    timeGrid.appendChild(slotDiv);
                });
            });
    }

    function validateCurrentStep() {
        const stepContainer = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const banner = document.getElementById('error-banner');
        let isValid = true;

        // 1. Validation Inputs, Selects, Textareas (sauf radios)
        const inputs = stepContainer.querySelectorAll('input[required]:not([type="radio"]), select[required], textarea[required]');
        inputs.forEach(input => {
            if (!input.value || !input.value.trim()) {
                input.classList.add('!border-red-500/50');
                isValid = false;
            } else {
                input.classList.remove('!border-red-500/50');
            }
        });

        if (currentStep === 1 && !isValid) {
            document.querySelector('.calendar-wrapper').classList.add('animate-shake', 'border-red-500/50');
            setTimeout(() => {
                document.querySelector('.calendar-wrapper').classList.remove('animate-shake');
            }, 500);
        }

        // 2. Validation Radios (Objectifs)
        const radioRequired = stepContainer.querySelector('input[type="radio"][required]');
        if (radioRequired) {
            const name = radioRequired.name;
            const isChecked = stepContainer.querySelector(`input[name="${name}"]:checked`);
            const cards = stepContainer.querySelectorAll('.objective-content');
            
            if (!isChecked) {
                cards.forEach(c => c.classList.add('invalid-card'));
                isValid = false;
            } else {
                cards.forEach(c => c.classList.remove('invalid-card'));
            }
        }

        // 3. Validation spéciale Étape 1
        if (currentStep === 1 && (!inputDate.value || !inputHour.value)) {
            isValid = false;
        }



        // Affichage de l'alerte
        if (!isValid) {
            banner.classList.remove('hidden');
            banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Petit effet de secousse pour attirer l'oeil
            banner.classList.add('animate-shake');
            setTimeout(() => banner.classList.remove('animate-shake'), 500);
        } else {
            banner.classList.add('hidden');
        }

        return isValid;
    }

    if (typeof formDataStorage === 'undefined') {
        var formDataStorage = {};
    }

    
   btnNext.addEventListener('click', function(e) {
        e.preventDefault();

        // On réinitialise l'erreur visuellement
        const banner = document.getElementById('error-banner');
        banner.classList.add('hidden');
        
        const stepContainer = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        if (!stepContainer) return;

        // --- AJOUT : On réinitialise l'affichage de l'erreur au clic ---
        const errorBanner = document.getElementById('error-banner');
        if (errorBanner) errorBanner.classList.add('hidden');
        // --------------------------------------------------------------

        if (validateCurrentStep()) {
            // --- 1. RÉCUPÉRATION (On le fait TOUJOURS, même à la fin) ---
            const fields = stepContainer.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                if (field.type === 'radio') {
                    if (field.checked) formDataStorage[field.name] = field.value;
                } else if (field.name) {
                    formDataStorage[field.name] = field.value;
                }
            });

            // --- 2. LOGIQUE DE SORTIE ---
            if (currentStep === 4) {
                // On cache l'alerte si elle était affichée
                const alertObj = document.getElementById('alert-objectifs');
                if(alertObj) alertObj.classList.add('hidden');
                
                // On lance l'envoi final avec TOUTES les données incluses
                sendDataToDatabase();
            } else {
                changeStep(1);
            }
        } else {
            // --- GESTION DE L'ALERTE ---
            banner.classList.remove('hidden');
            banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            if (currentStep === 4) {
                const alertObj = document.getElementById('alert-objectifs');
                if(alertObj) alertObj.classList.remove('hidden');
            }
        }
    });

    btnPrev.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentStep > 1) changeStep(-1);
    });

    function changeStep(direction) {
        const steps = document.querySelectorAll('.form-step');
        const totalSteps = steps.length; // On compte dynamiquement (ex: 4)

        // --- AJOUT : On cache l'erreur dès qu'on change d'étape ---
        const errorBanner = document.getElementById('error-banner');
        if (errorBanner) errorBanner.classList.add('hidden');

        // 1. Cacher l'étape actuelle
        steps[currentStep - 1].classList.remove('active');
        
        // 2. Changer d'index
        currentStep += direction;
        
        // 3. Afficher la nouvelle étape
        steps[currentStep - 1].classList.add('active');
        
        // --- MISE À JOUR BARRE DE PROGRESSION ---
        const progressLine = document.getElementById('progress-line');
        if (progressLine) {
            // On calcule le pourcentage par rapport au nombre réel d'étapes
            const percent = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressLine.style.width = `${percent}%`;
        }

        // --- MISE À JOUR DES CERCLES (Étapes en haut) ---
        document.querySelectorAll('.step-item').forEach(item => {
            const s = parseInt(item.dataset.step);
            item.classList.toggle('active', s === currentStep);
            const circle = item.querySelector('.step-circle');
            if (circle) {
                // S'allume si l'étape est atteinte ou dépassée
                circle.classList.toggle('!border-blue-500', s <= currentStep);
                circle.classList.toggle('!text-white', s <= currentStep);
                circle.classList.toggle('bg-blue-600', s <= currentStep); // Optionnel : fond bleu
            }
        });
        
        // --- GESTION DES BOUTONS ---
        // Cacher "Précédent" si on est au début
        btnPrev.classList.toggle('hidden', currentStep === 1);
        
        // Changer le texte du bouton "Suivant" en "Confirmer" si c'est la FIN
        const isLastStep = (currentStep === totalSteps);
        btnNext.querySelector('span').textContent = isLastStep ? "Confirmer le RDV" : "Suivant";

        // --- SCROLL AUTOMATIQUE ---
        // On remonte un peu au dessus de la carte pour bien voir le titre sur mobile
        const scrollTarget = auditCard.offsetTop - 50;
        window.scrollTo({ top: scrollTarget, behavior: 'smooth' });
    }

    // Navigation mois
    document.querySelectorAll('.btn-cal-nav').forEach((btn, index) => {
        btn.onclick = (e) => {
            e.preventDefault();
            currentNavDate.setMonth(currentNavDate.getMonth() + (index === 0 ? -1 : 1));
            renderCalendar(currentNavDate);
        };
    });

    renderCalendar(currentNavDate);


    function sendDataToDatabase() {
        // Affichage pour le debug
        console.log("Envoi des données...", formDataStorage);

        // On récupère le token CSRF pour Laravel
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;

        fetch('/api/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formDataStorage)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirection ou message de succès
                showSuccessModal();
            } else {
                alert("Erreur: " + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert("Une erreur est survenue lors de l'envoi.");
            if (btnNext) btnNext.disabled = false;
        });
    }

    function showSuccessModal() {
        const modal = document.getElementById('success-modal');
        const card = document.getElementById('modal-card');
        const countdownEl = document.getElementById('countdown-text');
        
        // Sécurité : Si la modale n'est pas dans le HTML, on alerte et on redirige
        if (!modal || !card) {
            console.warn("Éléments de la modale manquants, redirection directe.");
            window.location.href = '/';
            return;
        }

        // 1. Afficher le conteneur principal
        modal.classList.remove('hidden');
        
        // 2. Animation d'entrée (petit délai pour que le navigateur voit le retrait de 'hidden')
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);

        // 3. Gestion du compte à rebours
        let seconds = 15;
        const timer = setInterval(() => {
            seconds--;
            if (countdownEl) countdownEl.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = '/';
            }
        }, 1000);
    }

});