(function() {
    "use strict";

    // 1. Toggle de Password (Exactamente igual que estaba)
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

    // 2. Motor Swiper para FeelBig (Optimizado para requisitos)
    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            
            if (swiperEl && window.Swiper && !container.dataset.swiperReady) {
                const slides = swiperEl.querySelectorAll('.swiper-slide');
                const totalSlides = slides.length;

                try {
                    new Swiper(swiperEl, {
                        observer: true,
                        observeParents: true,
                        watchOverflow: true,
                        loop: totalSlides > 1, // Loop solo si hay más de uno
                        spaceBetween: 20,
                        slidesPerView: 1,
                        // Autodeslizamiento
                        autoplay: { 
                            delay: 4000, 
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true 
                        },
                        // Flechas
                        navigation: {
                            nextEl: container.querySelector('.swiper-button-next'),
                            prevEl: container.querySelector('.swiper-button-prev'),
                        },
                        // Paginación (Círculos)
                        pagination: {
                            el: container.querySelector('.swiper-pagination-custom'),
                            type: 'bullets',
                            clickable: true,
                        },
                        // Responsivo
                        breakpoints: {
                            768: { slidesPerView: Math.min(totalSlides, 2) },
                            1024: { slidesPerView: Math.min(totalSlides, 3) },
                            1300: { slidesPerView: Math.min(totalSlides, 4) }
                        },
                        on: {
                            init: function() {
                                container.dataset.swiperReady = 'true';
                            }
                        }
                    });
                } catch (e) {
                    console.error("Error al inicializar Swiper:", e);
                }
            }
        });
    };

    const init = () => { 
        initAuth(); 
        initCarousels(); 
    };

    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();