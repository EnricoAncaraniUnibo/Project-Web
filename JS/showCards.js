document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.event-container').forEach(container => {
        const prevBtn = container.querySelector('.prev');
        const nextBtn = container.querySelector('.next');
        
        if (!prevBtn && !nextBtn) return;
        
        const cards = container.querySelectorAll('[data-index]');
        let currentIndex = 0;
        
        function updateDisplay() {
            cards.forEach(card => {
                card.classList.remove('active');
                card.classList.add('d-none');
            });
            
            cards[currentIndex].classList.remove('d-none');
            cards[currentIndex].classList.add('active');
            
            if (prevBtn) prevBtn.disabled = currentIndex === 0;
            if (nextBtn) nextBtn.disabled = currentIndex === cards.length - 1;
            
            cards.forEach((card, index) => {
                const currentIndexSpan = card.querySelector('.current-index');
                if (currentIndexSpan) {
                    currentIndexSpan.textContent = index + 1;
                }
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateDisplay();
                }
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentIndex < cards.length - 1) {
                    currentIndex++;
                    updateDisplay();
                }
            });
        }
        
        updateDisplay();
    });
});