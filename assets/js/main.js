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

    // LÓGICA DE DETECCIÓN CORREGIDA
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] 🚀 INICIANDO COMPROBACIÓN SILENCIOSA...");
        
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');
        let sessionDetected = false;

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] 🛑 Ruta no válida para comprobación.");
            return;
        }

        if (!window.google) {
            console.error("[GoogleAuth] ❌ SDK de Google no cargado. Revisa el script en el HTML.");
            return;
        }

        console.log("[GoogleAuth] 🛠️ Configurando GSI con auto_select: true...");
        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            auto_select: true, // Esto es lo que debería evitar la ventana si ya hay sesión
            use_fedcm_for_prompt: true,
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ✅ SESIÓN DETECTADA AUTOMÁTICAMENTE.");
                
                // ESCRIBIMOS EL CÓDIGO AUTOMÁTICAMENTE SI EXISTE EL INPUT
                if (inputCodigo) {
                    console.log("[GoogleAuth] ✍️ Escribiendo token en el input...");
                    inputCodigo.value = response.credential;
                    // Disparamos evento input por si tienes frameworks escuchando
                    inputCodigo.dispatchEvent(new Event('input', { bubbles: true }));
                } else {
                    console.warn("[GoogleAuth] ⚠️ No se encontró el elemento 'inputCodigo'.");
                }

                if (statusMsg) {
                    console.log("[GoogleAuth] 🙈 Ocultando badge informativo.");
                    statusMsg.style.display = 'none';
                }
            }
        });

        console.log("[GoogleAuth] 📡 Ejecutando prompt() en modo silencioso...");
        google.accounts.id.prompt((notification) => {
            const moment = notification.getMomentType();
            console.log(`[GoogleAuth] ⏱️ Momento actual: ${moment}`);
            
            if (notification.isNotDisplayed()) {
                console.warn("[GoogleAuth] 🚫 El prompt no se mostró. Razón:", notification.getNotDisplayedReason());
            }
            
            if (notification.isSkippedMoment()) {
                console.warn("[GoogleAuth] ⏭️ El prompt fue omitido. Razón:", notification.getSkippedReason());
                console.log("[GoogleAuth] Detalle: Si ves 'unknown_reason', suele ser por falta de HTTPS o dominio no verificado.");
            }
        });

        // Verificación final
        setTimeout(() => {
            if (!sessionDetected) {
                console.log("[GoogleAuth] ❌ No se pudo recuperar la sesión solo. Mostrando aviso.");
                if (statusMsg) statusMsg.style.display = 'block';
            } else {
                console.log("[GoogleAuth] ✨ Proceso finalizado: Usuario logueado sin intervención.");
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
        console.log("[App] 🎬 Iniciando App...");
        initAuth(); 
        initGoogleAuth(); 
        initCarousels(); 
    };
    
    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();