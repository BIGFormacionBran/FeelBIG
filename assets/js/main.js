(function() {
    "use strict";

    const initAuth = () => {
        const passInput = document.getElementById('passInput');
        const toggleBtn = document.getElementById('toggleBtn');
        
        if (passInput && toggleBtn) {
            const iconOpen = toggleBtn.querySelector('.icon-open');
            const iconClosed = toggleBtn.querySelector('.icon-closed');

            toggleBtn.addEventListener('click', () => {
                const isPass = passInput.type === "password";
                
                // Cambiar el tipo de input
                passInput.type = isPass ? "text" : "password";
                
                // Alternar visibilidad de los SVGs
                if (isPass) {
                    // Ahora se ve la contraseña (ponemos el ojo tachado)
                    iconOpen.style.display = 'none';
                    iconClosed.style.display = 'block';
                    toggleBtn.setAttribute('aria-label', 'Ocultar contraseña');
                } else {
                    // Ahora está oculta (ponemos el ojo normal)
                    iconOpen.style.display = 'block';
                    iconClosed.style.display = 'none';
                    toggleBtn.setAttribute('aria-label', 'Mostrar contraseña');
                }
            });
        }
    };

    // --- El resto del código del carrusel se mantiene exactamente igual ---
    const carouselStates = {};

    window.moveCarousel = (id, direction) => {
        const container = document.getElementById(id);
        const track = container.querySelector('.carousel-track');
        if(!track) return;
        const slides = track.querySelectorAll('.carousel-slide');
        const gap = 20;
        const slideWidth = slides[0].offsetWidth + gap;
        const visibleSlides = Math.round(track.parentElement.offsetWidth / (slides[0].offsetWidth));
        const maxIndex = slides.length - visibleSlides;

        if (!carouselStates[id]) carouselStates[id] = { index: 0 };
        carouselStates[id].index += direction;
        if (carouselStates[id].index > maxIndex) carouselStates[id].index = 0;
        if (carouselStates[id].index < 0) carouselStates[id].index = maxIndex;

        updateCarouselUI(id, track, slideWidth);
    };

    window.gotoSlide = (id, index) => {
        const container = document.getElementById(id);
        const track = container.querySelector('.carousel-track');
        const slideWidth = track.querySelectorAll('.carousel-slide')[0].offsetWidth + 20;
        carouselStates[id] = { index: index };
        updateCarouselUI(id, track, slideWidth);
    };

    const updateCarouselUI = (id, track, width) => {
        track.style.transform = `translateX(-${carouselStates[id].index * width}px)`;
        const dots = track.closest('.carrusel-contenedor-global').querySelectorAll('.dot');
        dots.forEach((dot, i) => dot.classList.toggle('active', i === carouselStates[id].index));
    };

    const initCarousels = () => {
        document.querySelectorAll('.carrusel-contenedor-global').forEach(carousel => {
            const id = carousel.id;
            setInterval(() => window.moveCarousel(id, 1), 5000);
        });
    };

    const init = () => { initAuth(); initCarousels(); };
    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
    else init();
})();