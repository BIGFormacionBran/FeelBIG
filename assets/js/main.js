// Variable global para los estados de los carruseles
const carouselStates = {};

// 1. Funciones de movimiento globales
window.moveCarousel = (id, direction) => {
    const container = document.getElementById(id);
    if (!container) return;
    
    const track = container.querySelector('.carousel-track');
    const slides = track.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;

    // Calcular dimensiones dinámicas
    const slideWidth = slides[0].offsetWidth;
    const viewportWidth = container.querySelector('.carousel-main-viewport').offsetWidth;
    
    // Cuántas slides se ven al mismo tiempo
    const visibleSlides = Math.round(viewportWidth / slideWidth);
    const maxIndex = Math.max(0, slides.length - visibleSlides);

    if (!carouselStates[id]) carouselStates[id] = { index: 0 };
    
    carouselStates[id].index += direction;

    // Lógica circular (bucle)
    if (carouselStates[id].index > maxIndex) carouselStates[id].index = 0;
    if (carouselStates[id].index < 0) carouselStates[id].index = maxIndex;

    updateCarouselUI(id, track, slideWidth);
};

window.gotoSlide = (id, index) => {
    const container = document.getElementById(id);
    if (!container) return;
    const track = container.querySelector('.carousel-track');
    const slides = track.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;
    
    const slideWidth = slides[0].offsetWidth;
    carouselStates[id] = { index: index };
    
    updateCarouselUI(id, track, slideWidth);
};

const updateCarouselUI = (id, track, width) => {
    track.style.transform = `translateX(-${carouselStates[id].index * width}px)`;
    
    // Actualizar dots
    const dots = track.closest('.carrusel-contenedor-global').querySelectorAll('.dot');
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === carouselStates[id].index);
    });
};

// 2. Inicialización
(function() {
    "use strict";

    const initAuth = () => {
        const passInput = document.getElementById('passInput');
        const toggleBtn = document.getElementById('toggleBtn');
        if (passInput && toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const isPass = passInput.type === "password";
                passInput.type = isPass ? "text" : "password";
                toggleBtn.querySelector('.icon-open').style.display = isPass ? 'none' : 'block';
                toggleBtn.querySelector('.icon-closed').style.display = isPass ? 'block' : 'none';
            });
        }
    };

    const initCarousels = () => {
        document.querySelectorAll('.carrusel-contenedor-global').forEach(carousel => {
            const id = carousel.id;
            // Autoplay cada 5 segundos
            setInterval(() => {
                window.moveCarousel(id, 1);
            }, 5000);
        });
    };

    const init = () => { 
        initAuth(); 
        initCarousels(); 
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();