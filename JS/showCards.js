document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.event-container').forEach(container => {
        const prevBtn = container.querySelector('.prev');
        const nextBtn = container.querySelector('.next');
        
        if (!prevBtn && !nextBtn) return;
        
        // Trova tutte le card per questa città
        const cards = container.querySelectorAll('[data-index]');
        let currentIndex = 0;
        
        // Funzione per aggiornare la visualizzazione
        function updateDisplay() {
            // Nascondi tutte le card
            cards.forEach(card => {
                card.classList.remove('active');
                card.classList.add('d-none');
            });
            
            // Mostra la card corrente
            cards[currentIndex].classList.remove('d-none');
            cards[currentIndex].classList.add('active');
            
            // Aggiorna i pulsanti
            if (prevBtn) prevBtn.disabled = currentIndex === 0;
            if (nextBtn) nextBtn.disabled = currentIndex === cards.length - 1;
            
            // Aggiorna l'indicatore numerico in tutte le card
            cards.forEach((card, index) => {
                const currentIndexSpan = card.querySelector('.current-index');
                if (currentIndexSpan) {
                    currentIndexSpan.textContent = index + 1;
                }
            });
        }
        
        // Gestione click su pulsante "prev"
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateDisplay();
                }
            });
        }
        
        // Gestione click su pulsante "next"
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentIndex < cards.length - 1) {
                    currentIndex++;
                    updateDisplay();
                }
            });
        }
        
        // Inizializza la visualizzazione
        updateDisplay();
    });
});