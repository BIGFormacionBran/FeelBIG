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

    // LÓGICA DE DETECCIÓN CON LOGS EXHAUSTIVOS
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] Iniciando flujo de verificación detallado...");
        
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');
        let sessionDetected = false;

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] Ruta no válida para comprobación.");
            return;
        }

        if (!window.google) {
            console.error("[GoogleAuth] SDK de Google no encontrado. Revisa la carga del script.");
            return;
        }

        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            auto_select: true,
            use_fedcm_for_prompt: true,
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ¡CALLBACK RECIBIDO! Sesión validada correctamente.");
                console.log("[GoogleAuth] Token recibido (fragmento):", response.credential.substring(0, 20) + "...");
                if (statusMsg) statusMsg.style.display = 'none';
            }
        });

        // Para que el callback funcione, el PROMPT debe ejecutarse. 
        // Si auto_select está a true, será invisible si hay sesión.
        google.accounts.id.prompt((notification) => {
            console.log("[GoogleAuth] Estado del Prompt (MomentType):", notification.getMomentType());
            
            if (notification.isNotDisplayed()) {
                console.warn("[GoogleAuth] Prompt no mostrado. Razón:", notification.getNotDisplayedReason());
            }
            
            if (notification.isSkippedMoment()) {
                console.warn("[GoogleAuth] Momento omitido. Razón:", notification.getSkippedReason());
            }

            if (notification.isDismissedMoment()) {
                console.warn("[GoogleAuth] Momento descartado. Razón:", notification.getDismissedReason());
            }
        });

        // Verificación final tras un tiempo prudencial
        setTimeout(() => {
            if (!sessionDetected) {
                console.log("[GoogleAuth] Tras 3s no se confirmó sesión vía callback. Mostrando aviso visual.");
                if (statusMsg) statusMsg.style.display = 'block';
            } else {
                console.log("[GoogleAuth] Verificación final: Sesión activa confirmada.");
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