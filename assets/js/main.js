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

    // LÓGICA DE DETECCIÓN SILENCIOSA CORREGIDA
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] 🔍 Iniciando verificación 100% silenciosa...");
        
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo'); // El input donde escribiremos el código
        let sessionDetected = false;

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] 📍 Ruta actual no requiere validación de Google.");
            return;
        }

        if (!window.google) {
            console.error("[GoogleAuth] ❌ Error crítico: SDK de Google no cargado.");
            return;
        }

        console.log("[GoogleAuth] ⚙️ Configurando inicialización (auto_select: true)...");

        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            auto_select: true, // Intenta loguear sin interacción
            use_fedcm_for_prompt: true, // Obligatorio en navegadores modernos (Chrome 121+)
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ✅ ¡SESIÓN DETECTADA AUTOMÁTICAMENTE!");
                console.log("[GoogleAuth] 🆔 ID Token:", response.credential.substring(0, 30) + "...");

                // 1. Ocultamos el badge informativo si existiera
                if (statusMsg) {
                    console.log("[GoogleAuth] 🪄 Ocultando badge informativo.");
                    statusMsg.style.display = 'none';
                }

                // 2. ESCRIBIMOS EL CÓDIGO AUTOMÁTICAMENTE (Aquí pones tu lógica de 'código')
                if (inputCodigo) {
                    console.log("[GoogleAuth] ✍️ Escribiendo código en el input automáticamente...");
                    // Ejemplo: podrías extraer un código del token o simplemente marcar validado
                    inputCodigo.value = "LOGUEADO_GOOGLE"; 
                    // Si necesitas disparar un evento para que JS sepa que cambió:
                    inputCodigo.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        });

        console.log("[GoogleAuth] 📡 Ejecutando prompt en segundo plano...");

        google.accounts.id.prompt((notification) => {
            const moment = notification.getMomentType();
            console.log(`[GoogleAuth] 🕒 Momento del Prompt: ${moment}`);

            if (notification.isNotDisplayed()) {
                console.log(`[GoogleAuth] ℹ️ El prompt no se mostró. Razón: ${notification.getNotDisplayedReason()}`);
            }
            
            if (notification.isSkippedMoment()) {
                console.warn(`[GoogleAuth] ⚠️ Salto de flujo (Skipped). Razón: ${notification.getSkippedReason()}`);
                // Si la razón es 'user_cancel' o 'tap_outside', no hacemos nada, pero si es 'unknown_reason',
                // suele ser que no hay sesión activa en el navegador.
            }
        });

        // Verificación de seguridad tras 3.5 segundos
        setTimeout(() => {
            if (!sessionDetected) {
                console.log("[GoogleAuth] ❌ No se detectó sesión automática tras el tiempo de espera.");
                console.log("[GoogleAuth] 📢 Acción: Mostrando badge informativo para acción manual.");
                if (statusMsg) {
                    statusMsg.style.display = 'block';
                }
            } else {
                console.log("[GoogleAuth] ✨ Proceso finalizado con éxito: Sesión inyectada.");
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