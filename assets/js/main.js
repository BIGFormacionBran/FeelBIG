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

    const initGoogleAuth = () => {
        const statusMsg   = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');
        let sessionDetected = false;

        const MI_CLIENT_ID = "329236128668-fdvbaj10dklgcde11qj8os2mstdmirlv.apps.googleusercontent.com";

        if (!window.location.pathname.includes('register-confirm')) {
            return;
        }

        if (!window.google || !window.google.accounts) {
            return;
        }

        google.accounts.id.initialize({
            client_id: MI_CLIENT_ID,
            auto_select: true,
            use_fedcm_for_prompt: true,
            itp_support: true,
            callback: (response) => {
                sessionDetected = true;
                
                const formData = new FormData();
                formData.append('google_token', response.credential);
                formData.append('ajax_verify', 'true');

                fetch('/register-confirm', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "/home";
                    } else {
                        if (statusMsg) statusMsg.style.display = 'block';
                    }
                })
                .catch(() => {
                    // Error de red o servidor manejado silenciosamente
                });
            }
        });

        google.accounts.id.prompt();

        setTimeout(() => {
            if (!sessionDetected) {
                if (statusMsg) statusMsg.style.display = 'block';
            }
        }, 3500);
    };

    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            
            // Verificamos existencia y si ya está inicializado
            if (swiperEl && window.Swiper && !swiperEl.classList.contains('swiper-initialized')) {
                
                // CONTAR SLIDES: Si hay pocos, el loop dará error
                const slideCount = swiperEl.querySelectorAll('.swiper-slide').length;
                
                // Configuración dinámica
                const shouldLoop = slideCount > 3; // Solo hace loop si hay más de 3 items

                new Swiper(swiperEl, {
                    slidesPerView: 'auto',
                    spaceBetween: 30,
                    centeredSlides: shouldLoop, // Solo centramos si hay loop
                    loop: shouldLoop,
                    // Si no hay loop, quitamos los loopedSlides para evitar conflictos
                    ...(shouldLoop && { loopedSlides: 5 }), 
                    speed: 800,
                    autoplay: shouldLoop ? { delay: 3000, disableOnInteraction: false } : false,
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination-custom'),
                        clickable: true,
                    },
                    observer: true,
                    observeParents: true
                });

                // Ocultar flechas si no hay suficientes diapositivas
                if (slideCount <= 1) {
                    const navButtons = container.querySelectorAll('.btn-nav-feelbig');
                    navButtons.forEach(btn => btn.style.display = 'none');
                }
            }
        });
    };

    const init = () => {
        initAuth();
        initGoogleAuth();
        initCarousels();
    };

    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();