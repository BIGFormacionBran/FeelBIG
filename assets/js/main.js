(function() {
    "use strict";

    const initAuth = () => {
        const passInput = document.getElementById('passInput');
        const toggleBtn = document.getElementById('toggleBtn');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passInput && toggleBtn && eyeIcon) {
            const iconHidden = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>`;
            const iconVisible = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;
            toggleBtn.addEventListener('click', () => {
                const isPass = passInput.type === "password";
                passInput.type = isPass ? "text" : "password";
                eyeIcon.innerHTML = isPass ? iconVisible : iconHidden;
            });
        }
    };

    const carouselStates = {};

    window.moveCarousel = (id, direction) => {
        const container = document.getElementById(id);
        const track = container.querySelector('.carousel-track');
        const slides = track.querySelectorAll('.carousel-slide');
        const gap = 20;
        const slideWidth = slides[0].offsetWidth + gap;
        
        // Calculamos cuántos caben en pantalla
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