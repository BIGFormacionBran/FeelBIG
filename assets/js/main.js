// 1. Inicialización
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

    // --- MOTOR SWIPER ADAPTADO DE CREISS ---
    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            
            if (swiperEl && window.Swiper && !container.dataset.swiperReady) {
                const totalSlides = swiperEl.querySelectorAll('.swiper-slide').length;
                const canLoop = totalSlides > 3;

                try {
                    new Swiper(swiperEl, {
                        observer: true,
                        observeParents: true,
                        watchOverflow: true,
                        loop: canLoop,
                        autoplay: (totalSlides > 1) ? { 
                            delay: 5000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true 
                        } : false,
                        spaceBetween: 20,
                        slidesPerView: 1,
                        navigation: {
                            nextEl: container.querySelector('.swiper-button-next'),
                            prevEl: container.querySelector('.swiper-button-prev'),
                        },
                        pagination: {
                            el: container.querySelector('.swiper-pagination-custom'),
                            clickable: true,
                        },
                        breakpoints: {
                            768: { slidesPerView: Math.min(totalSlides, 2) },
                            1024: { slidesPerView: Math.min(totalSlides, 3) }
                        },
                        on: {
                            init: function() {
                                container.dataset.swiperReady = 'true';
                                if (totalSlides <= 1) {
                                    const ctrls = [this.navigation.nextEl, this.navigation.prevEl, this.pagination.el];
                                    ctrls.forEach(el => el && el.style.setProperty('display', 'none', 'important'));
                                }
                            }
                        }
                    });
                } catch (e) {
                    console.error("Error inicializando Swiper en FEELBIG:", e);
                }
            }
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