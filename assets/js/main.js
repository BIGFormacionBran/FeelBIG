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
        console.log("[GoogleAuth] 🔍 Iniciando comprobación silenciosa de sesión...");
        
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');
        let sessionDetected = false;

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] ⏹️ Ruta omitida: No es register-confirm.");
            return;
        }

        if (!window.google) {
            console.error("[GoogleAuth] ❌ SDK no encontrado.");
            return;
        }

        console.log("[GoogleAuth] ⚙️ Configurando One Tap en modo AUTO-SELECT...");

        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            auto_select: true, // INTENTA LOGUEAR SIN PREGUNTAR
            use_fedcm_for_prompt: true, // NECESARIO PARA EVITAR BLOQUEOS DE NAVEGADOR
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ✅ SESIÓN DETECTADA. No se mostró ventana.");
                
                // 1. Ocultamos el aviso informativo (Badge)
                if (statusMsg) {
                    console.log("[GoogleAuth] 🪄 Ocultando badge informativo.");
                    statusMsg.style.display = 'none';
                }

                // 2. ESCRIBIMOS EL CÓDIGO AUTOMÁTICAMENTE
                if (inputCodigo) {
                    console.log("[GoogleAuth] ✍️ Inyectando valor en el input...");
                    // Aquí el valor que quieras poner si se detecta la cuenta
                    inputCodigo.value = "SESION_GOOGLE_OK"; 
                    inputCodigo.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        });

        // Lanzamos el prompt pero con lógica de cancelación inmediata si no es automático
        google.accounts.id.prompt((notification) => {
            const moment = notification.getMomentType();
            const reason = notification.getNotDisplayedReason() || notification.getSkippedReason();
            
            console.log(`[GoogleAuth] 🕒 Estado actual: ${moment} | Detalle: ${reason}`);

            // SI LA VENTANA INTENTA SALIR O SE OMITE PORQUE NO HAY SESIÓN PREVIA:
            if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                console.log("[GoogleAuth] 🤐 Flujo silencioso mantenido (La ventana no salió).");
            }
        });

        // Verificación final a los 3 segundos
        setTimeout(() => {
            if (!sessionDetected) {
                console.warn("[GoogleAuth] ⚠️ No se encontró sesión activa en el navegador.");
                console.log("[GoogleAuth] 📢 Acción: Mostrando badge informativo para el usuario.");
                if (statusMsg) statusMsg.style.display = 'block';
            } else {
                console.log("[GoogleAuth] ✨ Éxito: Código autocompletado sin intervención.");
            }
        }, 3000);
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