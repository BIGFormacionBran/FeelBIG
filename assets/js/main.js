(function() {
    "use strict";

    const initAuth = () => {
        const passInput = document.getElementById('passInput');
        const toggleBtn = document.getElementById('toggleBtn');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passInput && toggleBtn && eyeIcon) {
            // Icono Ojo con línea (Ocultar/Facebook style)
            const iconHidden = `<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.293 2.293a1 1 0 1 1 1.414 1.414l-18 18a1 1 0 0 1-1.414-1.414l3.446-3.446c-.238-.188-.47-.387-.694-.6L1.31 12.722a.985.985 0 0 1 0-1.436l3.734-3.527c3.15-2.976 7.77-3.542 11.48-1.697l3.768-3.768zm-5.275 5.275c-2.852-1.138-6.23-.596-8.582 1.627l-2.974 2.808 2.974 2.809c.233.22.476.423.727.61l1.391-1.39a4 4 0 0 1 5.478-5.478l.986-.986zm-2.5 2.5a2.001 2.001 0 0 0-2.45 2.45l2.45-2.45z"></path><path d="M22.69 11.285 19.7 8.463l-1.414 1.414 2.251 2.126-2.973 2.809a8.099 8.099 0 0 1-6.377 2.164l-1.712 1.712c3.268.833 6.876.02 9.48-2.44l3.733-3.527a.985.985 0 0 0 0-1.436z"></path><path d="M15.997 12.167a4 4 0 0 1-3.83 3.83l3.83-3.83z"></path></svg>`;
            
            // Icono Ojo Abierto
            const iconVisible = `<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>`;

            // Inicializar con el icono de "Mostrar" (iconVisible) ya que el input nace como password
            eyeIcon.innerHTML = iconVisible;

            toggleBtn.addEventListener('click', () => {
                const isPass = passInput.type === "password";
                passInput.type = isPass ? "text" : "password";
                // Si ahora es texto, mostramos el icono de "tachar" (iconHidden)
                eyeIcon.innerHTML = isPass ? iconHidden : iconVisible;
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