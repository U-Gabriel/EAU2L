import './bootstrap';




document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('calendarGrid');
    const timeGrid = document.getElementById('timeGrid');
    if (!grid) return;

    let date = new Date();
    let offDays = [];

    const today = new Date();
    today.setHours(0, 0, 0, 0); 

    // Charger les jours "OFF" depuis la BDD
    fetch('/api/off-days')
    .then(res => {
        if (!res.ok) throw new Error("Erreur serveur");
        return res.json();
    })
    .then(data => {
        offDays = Array.isArray(data) ? data : [];
        renderCalendar();
    })
    .catch(err => {
        console.error("Impossible de charger les jours OFF, affichage par défaut", err);
        offDays = []; // On initialise à vide pour ne pas bloquer l'affichage
        renderCalendar();
    });

    function renderCalendar() {
        grid.innerHTML = "";
        const year = date.getFullYear();
        const month = date.getMonth();
        
        // Premier jour du mois
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Ajustement pour lundi (JS commence par dimanche=0)
        let startingDay = firstDay === 0 ? 6 : firstDay - 1;

        // Cases vides pour le début
        for (let i = 0; i < startingDay; i++) {
            grid.innerHTML += `<div class="cal-day empty"></div>`;
        }

        // Génération des jours
        for (let day = 1; day <= daysInMonth; day++) {
            const dateToCheck = new Date(year, month, day); // Utilise year et month déjà définis
            let fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            let isOff = offDays.includes(fullDate);
            let isWeekend = dateToCheck.getDay() % 6 === 0;
            let isPast = dateToCheck < today; // Vérification du passé

            // On combine les raisons de désactiver (Passé, Off, ou Weekend)
            let disabledClass = (isOff || isWeekend || isPast) ? 'disabled' : '';
            let pastStyle = isPast ? 'style="opacity: 0.3; cursor: not-allowed;"' : '';

            grid.innerHTML += `<div class="cal-day ${disabledClass}" data-date="${fullDate}" ${pastStyle}>${day}</div>`;
        }

        // Event listener sur les jours
        document.querySelectorAll('.cal-day:not(.disabled):not(.empty)').forEach(el => {
            el.addEventListener('click', (e) => {
                document.querySelectorAll('.cal-day').forEach(d => d.classList.remove('active'));
                e.target.classList.add('active');
                loadTimeSlots(e.target.dataset.date);
            });
        });
    }

    function loadTimeSlots(selectedDate) {
        timeGrid.innerHTML = "Chargement...";
        fetch(`/api/available-slots?date=${selectedDate}`)
            .then(res => res.json())
            .then(slots => {
                timeGrid.innerHTML = "";
                if (slots.length === 0) {
                    timeGrid.innerHTML = "<div class='text-white/50'>Aucun créneau libre</div>";
                    return;
                }
                slots.forEach(slot => {
                    timeGrid.innerHTML += `<div class="time-slot" data-time="${slot}">${slot}</div>`;
                });
                
                // Sélection de l'heure
                document.querySelectorAll('.time-slot').forEach(t => {
                    t.addEventListener('click', (e) => {
                        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                        e.target.classList.add('selected');
                        // On stocke dans un input caché pour le formulaire final
                        // document.getElementById('selected_hour').value = e.target.dataset.time;
                    });
                });
            });
    }
});