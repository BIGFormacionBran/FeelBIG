(function() {
    "use strict";

    // Función para enviar logs al servidor (PHP)
    const remoteLog = (message, level = 'ERROR') => {
        fetch('/includes/ajax/js_logger.php', {
            method: 'POST',
            body: JSON.stringify({ message, level }),
            headers: { 'Content-Type': 'application/json' }
        }).catch(() => {}); 
    };

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
            remoteLog("Google SDK no detectado en register-confirm", "WARNING");
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
                        remoteLog("Validación Google fallida: " + JSON.stringify(data));
                    }
                })
                .catch((err) => {
                    remoteLog("Error en fetch Google Auth: " + err.message);
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
                const slideCount = swiperEl.querySelectorAll('.swiper-slide').length;
                const shouldLoop = slideCount > 3;
                try {
                    new Swiper(swiperEl, {
                        spaceBetween: 20,
                        breakpoints: {
                            320: { slidesPerView: 1, centeredSlides: true },
                            768: { slidesPerView: 2, centeredSlides: false },
                            1024: { slidesPerView: 3, centeredSlides: false }
                        },
                        preloadImages: false,
                        lazy: true,
                        watchSlidesProgress: true,
                        powerMode: true,
                        spaceBetween: 30,
                        centeredSlides: shouldLoop, 
                        loop: shouldLoop,
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
                        observeParents: true,
                        on: {
                            init: function () {
                                swiperEl.classList.remove('is-loading');
                                swiperEl.style.visibility = 'visible';
                            },
                        }
                    });
                } catch (e) {
                    remoteLog("Error Swiper (" + container.id + "): " + e.message);
                    swiperEl.style.visibility = 'visible';
                    swiperEl.classList.remove('is-loading');
                }

                if (slideCount <= 1) {
                    const navButtons = container.querySelectorAll('.btn-nav-feelbig');
                    navButtons.forEach(btn => btn.style.display = 'none');
                }
            } else if (!window.Swiper && swiperEl) {
                remoteLog("Librería Swiper no cargada al intentar inicializar carrusel", "ERROR");
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