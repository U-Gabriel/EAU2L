let currentStep = 1;
let formDataStorage = {};

let isEmailVerified = false;
let isOtpSent = false;
let timerInterval; // Référence globale pour pouvoir l'arrêter proprement

document.addEventListener('DOMContentLoaded', function() {
    // 1. SELECTEURS & INIT
    const inputDate = document.getElementById('meeting_date');
    const inputHour = document.getElementById('meeting_hour');
    const grid = document.getElementById('calendarGrid'); 
    const timeGrid = document.getElementById('timeGrid');
    const btnNext = document.getElementById('nextBtn');
    const btnPrev = document.getElementById('prevBtn');
    let errorBanner = document.getElementById('error-banner');
    
    const auditCard = document.querySelector('.audit-card-premium');

    if (!grid || !btnNext || !inputDate) return;

    // Reset initial
    const form = document.getElementById('auditForm');
    if (form) form.reset();
    inputDate.value = "";
    inputHour.value = "";
    let currentNavDate = new Date();
    let offDays = [];

    // Chargement des jours fériés/off
    fetch('/api/off-days')
        .then(res => res.json())
        .then(data => { 
            offDays = data; 
            renderCalendar(currentNavDate); 
        });

    // 2. BANDEAU D'ERREUR (Création si inexistant)
    if (!errorBanner) {
        errorBanner = document.createElement('div');
        errorBanner.id = "error-banner";
        errorBanner.className = "mb-4 animate-in hidden text-red-400 bg-red-400/10 p-3 rounded-lg border border-red-400/20"; 
        errorBanner.innerHTML = `
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span class="font-medium text-xs">Veuillez remplir tous les champs obligatoires (*).</span>
            </div>`;
        
        const buttonContainer = document.querySelector('.flex.justify-between.items-center.mt-8');
        if (buttonContainer) {
            buttonContainer.parentNode.insertBefore(errorBanner, buttonContainer);
        } else if (auditCard) {
            auditCard.appendChild(errorBanner);
        }
    }

    // 3. LOGIQUE OTP & TIMER (FONCTIONS CORRIGÉES)

    function startResendTimer() {
        const btn = document.getElementById('resend-otp-btn');
        const timerSpan = document.getElementById('resend-timer');
        if(!btn || !timerSpan) return;

        let timeLeft = 60; // Variable locale pour le décompte réel
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        
        clearInterval(timerInterval); // On nettoie l'ancien timer
        timerInterval = setInterval(() => {
            timeLeft--;
            timerSpan.textContent = `(${timeLeft}s)`;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                timerSpan.textContent = "";
            }
        }, 1000);
    }

    function sendOtpProcess() {
        const emailInput = document.getElementById('email_input');
        const email = emailInput ? emailInput.value : '';
        const btnText = document.getElementById('nextBtnText');
        
        if (!email) {
            alert("Veuillez saisir votre email.");
            return;
        }

        btnText.textContent = "ENVOI...";
        btnNext.disabled = true;

        fetch('/send-otp', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value 
            },
            body: JSON.stringify({ email: email })
        })
        .then(res => res.json())
        .then(data => {
            btnNext.disabled = false;
            btnText.textContent = "Suivant";
            if (data.success) {
                isOtpSent = true;
                startResendTimer();
            } else {
                alert(data.message || "Erreur lors de l'envoi");
            }
        })
        .catch(() => {
            btnNext.disabled = false;
            btnText.textContent = "Suivant";
            alert("Erreur de connexion au serveur.");
        });
    }

    // --- DANS TA FONCTION verifyOtpProcess ---
    function verifyOtpProcess() {
        const codeInput = document.getElementById('otp_input');
        const code = codeInput.value.replace(/\s/g, ''); 
        const errorMsg = document.getElementById('otp-error');
        const btnText = document.getElementById('nextBtnText');
        
        if (code.length !== 6) {
            if(errorMsg) {
                errorMsg.textContent = "Veuillez saisir les 6 caractères du code.";
                errorMsg.classList.remove('hidden');
            }
            codeInput.classList.add('animate-shake', 'border-red-500');
            setTimeout(() => codeInput.classList.remove('animate-shake'), 500);
            return;
        }

        btnNext.disabled = true;
        btnText.textContent = "VÉRIFICATION...";

        fetch('/verify-otp', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value 
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            btnNext.disabled = false;
            btnText.textContent = "Suivant";

            if (data.success) {
                isEmailVerified = true; // On valide l'état
                if(errorMsg) errorMsg.classList.add('hidden');
                codeInput.classList.replace('border-red-500', 'border-green-500');
                
                // INDISPENSABLE : On force le passage à l'étape suivante ici !
                changeStep(1); 
            } else {
                isEmailVerified = false;
                if(errorMsg) {
                    errorMsg.textContent = "Code incorrect. Veuillez réessayer.";
                    errorMsg.classList.remove('hidden');
                }
                codeInput.classList.add('animate-shake', 'border-red-500');
                setTimeout(() => codeInput.classList.remove('animate-shake'), 500);
            }
        })
        .catch(err => {
            btnNext.disabled = false;
            btnText.textContent = "Suivant";
            if (errorMsg) {
                errorMsg.textContent = "Code incorrect. Veuillez réessayer.";
                errorMsg.classList.remove('hidden');
            }
            console.error("Erreur Fetch:", err);
        });
    }

    // 4. GESTION DU BOUTON SUIVANT (LOGIQUE PRINCIPALE)
    btnNext.addEventListener('click', function(e) {
        e.preventDefault();

        const stepContainer = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const fields = stepContainer.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            if (field.type === 'radio') {
                if (field.checked) formDataStorage[field.name] = field.value;
            } else if (field.name) {
                formDataStorage[field.name] = field.value;
            }
        });
        
        // Etape 2 -> 3 (Contact vers OTP)
        if (currentStep === 2) {
            if (validateCurrentStep()) {
                sendOtpProcess();
                changeStep(1);
            }
            return; // Bloque le changement d'étape automatique
        }

        // Etape 3 -> 4 (Vérification OTP)
        if (currentStep === 3) {
            if (!isEmailVerified) {
                verifyOtpProcess();
            } else {
                changeStep(1); // Permet de passer à la suite si on clique à nouveau
            }
            return; 
        }

        // Autres étapes normales
        if (validateCurrentStep()) {
            const stepContainer = document.querySelector(`.form-step[data-step="${currentStep}"]`);
            const fields = stepContainer.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                if (field.type === 'radio') {
                    if (field.checked) formDataStorage[field.name] = field.value;
                } else if (field.name) {
                    formDataStorage[field.name] = field.value;
                }
            });

            if (currentStep === 5) {
                sendDataToDatabase();
            } else {
                changeStep(1);
            }
        }
    });

    // 5. GESTION DU CALENDRIER & SLOTS (IDENTIQUE)
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

        const minDate = new Date();
        minDate.setDate(today.getDate() + 2); 
        minDate.setHours(0, 0, 0, 0);

        for (let x = 0; x < offset; x++) {
            grid.insertAdjacentHTML('beforeend', '<div class="cal-day empty opacity-0"></div>');
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'cal-day transition-all duration-300 hover:scale-105 cursor-pointer p-2 sm:p-4 rounded-lg text-center text-white font-medium border border-white/5 bg-white/5';
            dayDiv.textContent = i;
            
            const checkDate = new Date(year, month, i);
            const fullDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

            // le design de la case du jour
            const now = new Date();
            const isToday = checkDate.getDate() === now.getDate() && 
                            checkDate.getMonth() === now.getMonth() && 
                            checkDate.getFullYear() === now.getFullYear();

            if (isToday) {
                dayDiv.classList.add('is-today');
            }

            if (checkDate < minDate || checkDate.getDay() === 0 || checkDate.getDay() === 6 || offDays.includes(fullDateStr)) {
                dayDiv.classList.add('disabled', 'opacity-20');
                dayDiv.style.pointerEvents = 'none';
            } else {
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

    // 6. VALIDATION & NAVIGATION
    function validateCurrentStep() {
        const stepContainer = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const banner = document.getElementById('error-banner');
        let isValid = true;

        const inputs = stepContainer.querySelectorAll('input[required]:not([type="radio"]), select[required], textarea[required]');
        inputs.forEach(input => {
            if (!input.value || !input.value.trim()) {
                input.classList.add('!border-red-500/50');
                isValid = false;
            } else {
                input.classList.remove('!border-red-500/50');
            }
        });

        if (currentStep === 1 && (!inputDate.value || !inputHour.value)) isValid = false;

        const radioRequired = stepContainer.querySelector('input[type="radio"][required]');
        if (radioRequired) {
            const name = radioRequired.name;
            const isChecked = stepContainer.querySelector(`input[name="${name}"]:checked`);
            const allCards = stepContainer.querySelectorAll('.objective-card')
            
            if (!isChecked) {
                isValid = false;
                allCards.forEach(card => {
                    card.classList.add('error-border'); // On utilise la classe CSS créée plus haut
                    card.classList.add('animate-shake'); // Optionnel : petit effet visuel
                });
            } else {
                allCards.forEach(card => {
                    card.classList.remove('error-border');
                    card.classList.remove('animate-shake');
                });
            }
        }

        if (!isValid) {
            banner.classList.remove('hidden');
            banner.classList.add('animate-shake');
            setTimeout(() => banner.classList.remove('animate-shake'), 500);
        } else {
            banner.classList.add('hidden');
        }
        return isValid;
    }

    function changeStep(direction) {
        const steps = document.querySelectorAll('.form-step');
        const progressItems = document.querySelectorAll('.step-item');
        const progressLine = document.getElementById('progress-line');

        steps[currentStep - 1].classList.remove('active');
        currentStep += direction;
        steps[currentStep - 1].classList.add('active');

        btnPrev.classList.toggle('hidden', currentStep === 1);
        
        const nextBtnText = document.getElementById('nextBtnText');
        nextBtnText.textContent = (currentStep === 5) ? 'Confirmer le RDV' : 'Suivant';

        const progressPercent = ((currentStep - 1) / (steps.length - 1)) * 100;
        if(progressLine) progressLine.style.width = `${progressPercent}%`;

        progressItems.forEach((item, idx) => {
            const circle = item.querySelector('.step-circle');
            if (idx < currentStep) {
                circle.classList.add('bg-blue-600', 'border-blue-500', 'text-white');
                circle.classList.remove('text-white/30');
            } else {
                circle.classList.remove('bg-blue-600', 'border-blue-500', 'text-white');
                circle.classList.add('text-white/30');
            }
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Bouton Précédent
    btnPrev.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentStep > 1) changeStep(-1);
    });

    // Bouton de renvoi OTP
    const resendBtn = document.getElementById('resend-otp-btn');
    if(resendBtn) {
        resendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();   // Empêche l'événement de "remonter" vers le formulaire
            sendOtpProcess();
        });
    }

    // Navigation mois calendrier
    document.querySelectorAll('.btn-cal-nav').forEach((btn, index) => {
        btn.onclick = (e) => {
            e.preventDefault();
            currentNavDate.setMonth(currentNavDate.getMonth() + (index === 0 ? -1 : 1));
            renderCalendar(currentNavDate);
        };
    });

    // 7. ENVOI FINAL & MODAL
    function sendDataToDatabase() {
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
                showSuccessModal();
            } else {
                alert("Erreur: " + data.message);
            }
        })
        .catch(() => {
            alert("Une erreur est survenue lors de l'envoi.");
        });
    }

    function showSuccessModal() {
        const modal = document.getElementById('success-modal');
        const card = document.getElementById('modal-card');
        const countdownEl = document.getElementById('countdown-text');
        if (!modal || !card) {
            window.location.href = '/';
            return;
        }
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
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

    document.querySelectorAll('input[name="rdv_objective"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const stepContainer = this.closest('.form-step');
            const allCards = stepContainer.querySelectorAll('.objective-card');
            const banner = document.getElementById('error-banner');
            
            allCards.forEach(card => card.classList.remove('error-border'));
            if (banner) banner.classList.add('hidden');
        });
    });
});