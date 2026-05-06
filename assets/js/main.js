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
        console.log("[GoogleAuth] 🚀 INICIANDO COMPROBACIÓN SILENCIOSA...");
        
        const statusMsg   = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');
        let sessionDetected = false;

        // --- CONFIGURACIÓN REQUERIDA ---
        // SUSTITUYE ESTO POR TU ID REAL DE GOOGLE CLOUD CONSOLE
        const MI_CLIENT_ID = "329236128668-fdvbaj10dklgcde11qj8os2mstdmirlv.apps.googleusercontent.com.apps.googleusercontent.com";

        if (!window.location.pathname.includes('register-confirm')) {
            return;
        }

        if (!window.google || !window.google.accounts) {
            console.error("[GoogleAuth] ❌ SDK no disponible.");
            return;
        }

        google.accounts.id.initialize({
            client_id: MI_CLIENT_ID,
            auto_select: true,
            use_fedcm_for_prompt: true,
            itp_support: true,
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ✅ SESIÓN ENCONTRADA.");
                
                if (inputCodigo) {
                    inputCodigo.value = response.credential;
                    inputCodigo.dispatchEvent(new Event('input', { bubbles: true }));
                }

                if (statusMsg) statusMsg.style.display = 'none';
            }
        });

        // Este prompt es el que Google intenta ejecutar. 
        // Si hay error 401, aquí es donde muere.
        google.accounts.id.prompt((notification) => {
            const moment = notification.getMomentType();
            const reason = notification.getNotDisplayedReason() || notification.getSkippedReason() || "N/A";
            
            console.log(`[GoogleAuth] 🕒 Notificación: ${moment} | Razón: ${reason}`);

            // Si falla por 'invalid_client', entrará por aquí:
            if (notification.isNotDisplayed() && reason === "invalid_client") {
                console.error("[GoogleAuth] 🚨 Error Crítico: ID de cliente inválido o dominio no autorizado.");
            }
        });

        setTimeout(() => {
            if (!sessionDetected) {
                console.log("[GoogleAuth] 📢 No se detectó cuenta o hubo error 401. Badge visible.");
                if (statusMsg) statusMsg.style.display = 'block';
            }
        }, 3500);
    };

    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            if (swiperEl && window.Swiper && !swiperEl.classList.contains('swiper-initialized')) {
                new Swiper(swiperEl, {
                    slidesPerView: 'auto',
                    spaceBetween: 30,
                    centeredSlides: true,
                    loop: true,
                    loopedSlides: 5,
                    speed: 800,
                    autoplay: { delay: 3000, disableOnInteraction: false },
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