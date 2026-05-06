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

    // LÓGICA DE DETECCIÓN 100% SILENCIOSA
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] 🚀 INICIANDO COMPROBACIÓN SILENCIOSA...");
        
        const statusMsg   = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');
        let sessionDetected = false;

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] ⏹️ Ruta omitida. No requiere validación.");
            return;
        }

        if (!window.google || !window.google.accounts) {
            console.error("[GoogleAuth] ❌ SDK no disponible.");
            return;
        }

        // 1. INICIALIZACIÓN
        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com",
            auto_select: true,             // Tenta loguear automáticamente si hay una sesión
            use_fedcm_for_prompt: true,    // Obligatorio para evitar bloqueos modernos de Chrome
            itp_support: true,
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ✅ SESIÓN ENCONTRADA. Escribiendo código...");
                
                // RELLENAR INPUT AUTOMÁTICAMENTE
                if (inputCodigo) {
                    inputCodigo.value = response.credential;
                    inputCodigo.dispatchEvent(new Event('input', { bubbles: true }));
                    console.log("[GoogleAuth] ✍️ Token insertado en #inputCodigo.");
                }

                // OCULTAR BADGE SI EXISTE
                if (statusMsg) {
                    statusMsg.style.display = 'none';
                    console.log("[GoogleAuth] 🙈 Badge ocultado.");
                }
            }
        });

        // 2. LANZAMIENTO DEL PROMPT (MODO SILENCIOSO)
        // Si auto_select funciona, el callback se dispara y NO sale ventana.
        // Si no funciona, el prompt muere en 'skipped' y NO sale ventana.
        google.accounts.id.prompt((notification) => {
            const moment = notification.getMomentType();
            const reason = notification.getNotDisplayedReason() || notification.getSkippedReason() || "N/A";
            
            console.log(`[GoogleAuth] 🕒 Notificación: ${moment} | Razón: ${reason}`);

            if (notification.isDisplayMoment()) {
                console.warn("[GoogleAuth] ⚠️ Intentó mostrar UI. Esto no debería pasar con auto_select.");
            }

            if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                console.log("[GoogleAuth] 🤐 Flujo mantenido en segundo plano (Ventana bloqueada/omitida).");
            }
        });

        // 3. VERIFICACIÓN FINAL (TIMEOUT)
        // Si después de 3.5 segundos no hubo callback, asumimos que no hay sesión.
        setTimeout(() => {
            console.log("[GoogleAuth] 🏁 Finalizando espera de 3.5s...");
            if (!sessionDetected) {
                console.log("[GoogleAuth] 📢 No se detectó cuenta. Mostrando badge informativo.");
                if (statusMsg) statusMsg.style.display = 'block';
            } else {
                console.log("[GoogleAuth] ✨ Proceso automático completado con éxito.");
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