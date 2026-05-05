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
                const totalSlides = swiperEl.querySelectorAll('.swiper-slide').length;
                
                // IMPORTANTE: Solo activar loop si hay suficientes slides para evitar el Warning
                const shouldLoop = totalSlides > 3;

                try {
                    new Swiper(swiperEl, {
                        observer: true,
                        observeParents: true,
                        watchOverflow: true,
                        loop: shouldLoop,
                        spaceBetween: 20,
                        slidesPerView: "auto", // Permite que el CSS mande el ancho de 320px
                        centeredSlides: false,
                        autoplay: { 
                            delay: 5000, 
                            disableOnInteraction: false,
                        },
                        navigation: {
                            nextEl: container.querySelector('.swiper-button-next'),
                            prevEl: container.querySelector('.swiper-button-prev'),
                        },
                        pagination: {
                            el: container.querySelector('.swiper-pagination-custom'),
                            clickable: true,
                        },
                        on: {
                            init: function() {
                                container.dataset.swiperReady = 'true';
                            }
                        }
                    });
                } catch (e) {
                    console.error("Error Swiper:", e);
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