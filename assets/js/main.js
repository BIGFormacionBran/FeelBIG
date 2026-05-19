(function() {
    "use strict";

    const remoteLog = (message, level = 'ERROR') => {
        // Ruta actualizada a CamelCase
        fetch('/includes/ajax/JsLogger.php', {
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
        const inputCode = document.getElementById('inputCodigo'); // Variable en inglés
        let sessionDetected = false;

        const MY_CLIENT_ID = "329236128668-fdvbaj10dklgcde11qj8os2mstdmirlv.apps.googleusercontent.com";

        if (!window.location.pathname.includes('register-confirm')) {
            return;
        }

        if (!window.google || !window.google.accounts) {
            remoteLog("Google SDK no detectado en register-confirm", "WARNING");
            return;
        }

        google.accounts.id.initialize({
            client_id: MY_CLIENT_ID,
            auto_select: true,
            use_fedcm_for_prompt: true,
            itp_support: true,
            callback: (response) => {
                sessionDetected = true;
                
                const formData = new FormData();
                formData.append('google_token', response.credential);
                formData.append('ajax_verify', 'true');

                fetch('/ProcessConfirmation.php', { // Ruta actualizada
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "/Home.php"; // Ruta actualizada
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
        const carouselSections = document.querySelectorAll('.feelbig-swiper-section');
        
        carouselSections.forEach(container => {
            const swiperEl = container.querySelector('.swiper');
            if (window.Swiper && swiperEl) {
                const slideCount = swiperEl.querySelectorAll('.swiper-slide').length;
                
                try {
                    new Swiper(swiperEl, {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: slideCount > 1,
                        autoplay: slideCount > 1 ? {
                            delay: 5000, 
                            disableOnInteraction: false, 
                            pauseOnMouseEnter: true, 
                        } : false,
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